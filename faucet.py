import os
import logging
from urllib.parse import urlencode
from urllib.request import Request, urlopen
from urllib.error import URLError, HTTPError
import json
import mysql.connector
from time import sleep, time
from datetime import datetime

mysql_config = {
  'user': 'root',
  'password': '',
  'host': '127.0.0.1',
  'database': 'app_pool',
  'raise_on_warnings': True,
}
attempt = 0
xrb_address = "xrb_1bawnqs6cc9a91ziwoy7fgmdycyjk9r6dt7eerfgzdbysrfnbihfxzobt8c8"

def log_insert(data):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor()
	
	query = ("INSERT INTO cron_history (cronDuration, cronTime, cronType, cronContent) VALUES (%(duration)s, %(time)s, 'faucet', %(content)s)")
	
	cursor.execute(query, data)
	cnx.commit()
	cursor.close()
	cnx.close()


def log_clear(claimCaptchas, claimStatus):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor()
	
	query = ("TRUNCATE cron_history")
	
	cursor.execute(query)
	cnx.commit()
	cursor.close()
	cnx.close()

def log_count():
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)
	query = "SELECT COUNT(*) count FROM cron_history"
	cursor.execute(query)
	log = cursor.fetchone()
	cursor.close()
	cnx.close()
	return(log)

def set_claim_status(status, captchas):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor()

	format_strings = ','.join(['%s'] * len(captchas))
	cursor.execute("UPDATE pending_claims SET claimStatus = '" + status + "' WHERE claimCaptcha IN (%s)" % format_strings, tuple(captchas))
	cnx.commit()
	cursor.close()
	cnx.close()

def collect_pending_claims():
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)
	query = "SELECT * FROM pending_claims WHERE claimStatus = 'p' ORDER BY claimTime"
	cursor.execute(query)
	pending_claims = cursor.fetchall()
	cursor.close()
	cnx.close()
	return(pending_claims)

def elaborate(captchas, retry):
	global attempt
	url = 'https://faucet.raiblockscommunity.net/elaborate.php' # Set destination URL here

	try:
		print("Trying to elaborate, attemp #{}".format(retry+1))

		attempt += 1
		data = {}
		data['ask_address'] = xrb_address
		data['donate'] = 1
		data['accepted'] = 1
		data['captchas'] = json.dumps(captchas)

		request = Request(url, urlencode(data).encode())
		ret = urlopen(request).read().decode()
		
		return json.loads(str(ret))
	except HTTPError as e:
		if retry < 3:
			retry += 1
			elaborate(captchas, retry)

def main():
	start_time = time()
	print("[INFO] Starting at {}".format(datetime.utcnow()))

	arrCaptcha = []
	arrClaimId = []
	arrValid = []
	arrValidId = []

	print("[INFO] Collecting pending claims")
	captchas = collect_pending_claims()
	for captcha in captchas:
		arrCaptcha.append(captcha[2])
		arrClaimId.append(captcha[0])

	if len(arrCaptcha) > 0:
		print("[INFO] Attempting to elaborate {:,} claims".format(len(arrCaptcha)))
		ret = elaborate(arrCaptcha, 0)
		if ret is not None:
			error = ret["error"]

			if error == "no":
				print("[INFO] Checking valid claims")
				for valid in ret["valid_captchas"]:
					arrValid.append(arrCaptcha[int(valid)])
					arrValidId.append(arrClaimId[int(valid)])

					arrCaptcha[int(valid)] = ''

				print("[INFO] Storing valid claims")
				set_claim_status("d", arrValid)

				print("[INFO] Storing invalid claims")
				set_claim_status("i", arrCaptcha)
			else:
				print("[ERROR] Error while elaborating '{}'".format(error))
				if error == "Invalid claims.":
					print("[INFO] Storing invalid claims")
					set_claim_status("i", arrCaptcha)
				elif error == "Faucet maintenance.":
					set_claim_status("i", arrCaptcha)

			cronContent = {
				"claimsSent": json.dumps(arrClaimId),
				"serverError": error,
				"serverValidClaims": json.dumps(arrValidId),
				"validationAttemps": attempt
			}

			if log_count()[0] > 1000:
				print("[INFO] Clearing log")
				log_clear()

			print("[INFO] Inserting log")
			data = {
				"duration": "{:.3f} ms".format(time() - start_time),
				"time": "{:%Y-%m-%d %H:%M:%S}".format(datetime.utcnow()),
				"content": json.dumps(cronContent)
			}
			log_insert(data)
	else:
		print("[INFO] No pending claims")

	print("[INFO] Finishing at {}".format(datetime.utcnow()))
	print("")

if __name__ == '__main__':
    main()
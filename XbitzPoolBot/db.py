import time
import mysql.connector
from datetime import datetime

mysql_config = {
  "user": "root",
  "password": "",
  "host": "127.0.0.1",
  "database": "app_pool",
  "raise_on_warnings": True,
  "use_unicode": True,
  "charset": "utf8",
}

def db_register(data):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor()
	
	add_user = ("INSERT INTO pool_bot "
			  "(userId, accountId, chatId) "
			  "VALUES (%(userId)s, %(accountId)s, %(chatId)s)")
	
	cursor.execute(add_user, data)
	cnx.commit()
	
	cursor.close()
	cnx.close()

def db_select_user(userId):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT t1.userId, t2.accountId, t2.accountName, t2.accountAddress, t2.accountThreshold, t2.accountBalance FROM pool_bot t1 JOIN app_account t2 ON t2.accountId = t1.accountId WHERE t1.userId = {0}".format(userId)
	
	cursor.execute(query)
	account = cursor.fetchone()
	
	cursor.close()
	cnx.close()

	return(account)

def db_account_exist(accountAddress):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT accountId FROM app_account WHERE accountAddress = '{}'".format(accountAddress)
	
	cursor.execute(query)
	account = cursor.fetchone()
	
	cursor.close()
	cnx.close()

	return(account)

def db_user_exist(accountAddress):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT t1.userId FROM pool_bot t1 JOIN app_account t2 ON t2.accountId = t1.accountId WHERE t2.accountAddress = '{}'".format(accountAddress)
	
	cursor.execute(query)
	account = cursor.fetchone()
	
	cursor.close()
	cnx.close()

	return(account)

def db_pending(accountId, accountThreshold):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT * FROM payout_history WHERE payoutStatus = 'p' AND accountId = '{}' AND payoutQty > {}".format(accountId, int(accountThreshold))
	
	cursor.execute(query)
	pending = cursor.fetchone()
	
	cursor.close()
	cnx.close()

	return(pending)

def db_paid(accountId):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT * FROM payout_history WHERE payoutStatus = 'c' AND accountId = '{}' ORDER BY payoutTime DESC LIMIT 5".format(accountId)
	
	cursor.execute(query)
	paid = cursor.fetchall()
	
	cursor.close()
	cnx.close()

	return(paid)

def db_today_distributions(accountId, date):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT * FROM app_account_claim WHERE accountId = '{}' AND DATE(createdTime) = '{}' ORDER BY createdTime DESC".format(accountId, date)
	
	cursor.execute(query)
	distributions = cursor.fetchall()
	
	cursor.close()
	cnx.close()

	return(distributions)

def db_paylist(accountId):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT COALESCE(SUM(t1.claimStatus = 'p'), 0) pending, COALESCE(SUM(t1.claimStatus = 'd'), 0) valid, COALESCE(SUM(t1.claimStatus = 'i'), 0) invalid FROM pending_claims t1 WHERE t1.accountId = {}".format(accountId)
	
	cursor.execute(query)
	paylist = cursor.fetchone()
	
	cursor.close()
	cnx.close()

	return(paylist)

def db_last_distribution():
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT createdTime FROM app_account_claim ORDER BY createdTime DESC LIMIT 1"
	
	cursor.execute(query)
	last_distribution = cursor.fetchone()
	
	cursor.close()
	cnx.close()

	return(last_distribution)

def db_last_distributions(datetime):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT t2.chatId, t2.accountId, t1.claimQty, t1.claimMrai, t3.accountBalance, t3.accountThreshold, t1.claimRate FROM app_account_claim t1 JOIN pool_bot t2 ON t2.accountId = t1.accountId JOIN app_account t3 ON t3.accountId = t1.accountId WHERE t1.createdTime > '{}'".format(datetime)
	
	cursor.execute(query)
	last_distributions = cursor.fetchall()
	
	cursor.close()
	cnx.close()

	return(last_distributions)

def db_current_distributions(datetime):
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT t2.chatId, t2.accountId, t1.claimQty, t1.claimMrai, t3.accountBalance, t3.accountThreshold, t1.claimRate FROM app_account_claim t1 JOIN pool_bot t2 ON t2.accountId = t1.accountId JOIN app_account t3 ON t3.accountId = t1.accountId WHERE t1.createdTime = '{}'".format(datetime)
	
	cursor.execute(query)
	last_distributions = cursor.fetchall()
	
	cursor.close()
	cnx.close()

	return(last_distributions)

def db_members():
	cnx = mysql.connector.connect(**mysql_config)
	cursor = cnx.cursor(buffered=True)

	query = "SELECT chatId FROM pool_bot"
	
	cursor.execute(query)
	members = cursor.fetchall()
	
	cursor.close()
	cnx.close()

	return(members)
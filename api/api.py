import math
import json
from easyraikit import *
from flask import Flask, Response, request, jsonify

app = Flask(__name__)
rai = Rai()
unlocked = rai.password_enter({'wallet': wallet, 'password': wallet_password})['valid']

HOST_ORIGIN = 'http://xbitzpool.com'

lastPayoutId = "0";

@app.route("/")
def block_count():
	block_count = rai.block_count()
	message = {
		'status': 200,
		'message': {
			'block': {
				'count': block_count['count'],
				'unchecked': block_count['unchecked'],
			},
			'unlocked': unlocked,
			'version': rai.version()['node_vendor']
		},
	}
	resp = jsonify(message)
	resp.status_code = 200

	return resp

@app.route("/accounts", methods=['GET'])
def account_list():
	global wallet
	account_list = rai.account_list({'wallet': wallet})
	message = {
		'status': 200,
		'message': {
			'accounts': account_list['accounts']
		},
	}
	resp = jsonify(message)
	resp.status_code = 200

	return resp

@app.route("/accounts/<account>", methods=['GET'])
def account_detail(account):
	account_balance = rai.account_balance({'account': account})
	message = {
		'status': 200,
		'message': {
			'balance': {
				'balance': raiblocks_mrai_from_raw(int(account_balance['balance'])),
				'pending': raiblocks_mrai_from_raw(int(account_balance['pending']))
			}
		},
	}
	resp = jsonify(message)
	resp.status_code = 200

	return resp

@app.route("/send", methods=['POST'])
def send():
	global wallet
	global lastPayoutId
	if lastPayoutId == request.form['payoutId']:
		message = {
			'status': 200,
			'message': 'double'
		}
		resp = jsonify(message)
		resp.status_code = 200
		resp.headers.add('Access-Control-Allow-Origin', HOST_ORIGIN)

		return resp

	account_list = rai.account_list({'wallet': wallet})
	sender = account_list['accounts'][0]
	account = request.form['account']
	amount = raiblocks_mrai_to_raw(float(request.form['amount']))

	block = rai.send({'wallet': wallet, 'source': sender, 'destination': account, 'amount': amount})
	if block is not None:
		if block['block'] != "0000000000000000000000000000000000000000000000000000000000000000":
			message = {
				'status': 200,
				'message': {
					'block': block['block']
				}
			}
		else:
			message = {
				'status': 200,
				'message': 'error',
				'error': block['error']
			}
	else:
		message = {
			'status': 200,
			'message': 'error',
			'error': block['error']
		}

	resp = jsonify(message)
	resp.status_code = 200
	resp.headers.add('Access-Control-Allow-Origin', HOST_ORIGIN)

	return resp

if __name__ == "__main__":
	app.run()

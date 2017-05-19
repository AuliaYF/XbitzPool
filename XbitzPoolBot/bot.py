import logging
import requests
from db import *
from lang import *
from common import *
from functools import wraps
from datetime import (datetime, timedelta)
from telegram.ext import Updater, CommandHandler, Job
from telegram import (ReplyKeyboardMarkup, ChatAction, ParseMode)
from telegram.error import (TelegramError, Unauthorized, BadRequest, TimedOut, ChatMigrated, NetworkError)

logging.basicConfig(format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', level=logging.INFO, filename="bot.log")
logger = logging.getLogger(__name__)

TOKEN = "";

# Specify bot admins
LIST_OF_ADMINS = []
xrb_address = 'xrb_1bawnqs6cc9a91ziwoy7fgmdycyjk9r6dt7eerfgzdbysrfnbihfxzobt8c8'

lastDistribution = db_last_distribution()[0]

def typing_illusion(bot, chat_id):
	try:
		bot.sendChatAction(chat_id=chat_id, action=ChatAction.TYPING) # typing illusion
	except Unauthorized:
		return False
	except BadRequest:
		return False
	except:
		sleep(0.3)
		bot.sendChatAction(chat_id=chat_id, action=ChatAction.TYPING) # typing illusion

def restricted(func):
    @wraps(func)
    def wrapped(bot, update, *args, **kwargs):
        # extract user_id from arbitrary update
        try:
            user_id = update.message.from_user.id
        except (NameError, AttributeError):
            try:
                user_id = update.inline_query.from_user.id
            except (NameError, AttributeError):
                try:
                    user_id = update.chosen_inline_result.from_user.id
                except (NameError, AttributeError):
                    try:
                        user_id = update.callback_query.from_user.id
                    except (NameError, AttributeError):
                        print("No user_id available in update.")
                        return
        if user_id not in LIST_OF_ADMINS:
            print("Unauthorized access denied for {}.".format(user_id))
            return
        return func(bot, update, *args, **kwargs)
    return wrapped


def send_msg(bot, userId, msg):
	try:
		bot.sendMessage(chat_id=userId, parse_mode=ParseMode.MARKDOWN, text=msg)
		return True
	except Unauthorized:
		return False
	except BadRequest:
		return False

def send_msg_kbrd(bot, userId, msg, markup):
	bot.sendMessage(chat_id=userId, parse_mode=ParseMode.MARKDOWN, text=msg, reply_markup=markup)

def notify_distribution(bot, job):
	global lastDistribution

	tempDistribution = db_last_distribution()[0]

	if tempDistribution != lastDistribution:
		distributions = db_last_distributions(lastDistribution);

		for distribution in distributions:
			typing_illusion(bot, distribution[0])
			if send_msg(bot, distribution[0], lang("notify_distribution").format(int(distribution[2]), float(distribution[3]), float(distribution[6]), float(distribution[4]))):			
				pending_po = db_pending(distribution[1], distribution[5])
				if pending_po is None:
					send_msg(bot, distribution[0], lang("no_pending"))
				else:
					send_msg(bot, distribution[0], lang("has_pending").format(datetime.strftime(pending_po[4], "%b %d %H:%M:%S"), float(pending_po[3])))

		lastDistribution = tempDistribution

@restricted
def notify(bot, update):
	global lastDistribution

	userId = update.message.from_user.id

	tempDistribution = db_last_distribution()[0]
	distributions = db_current_distributions(tempDistribution);

	typing_illusion(bot, userId)
	send_msg(bot, userId, lang("processing_broadcast").format('Distribution Notification {}'.format(tempDistribution), len(distributions)))

	cnt = 0
	for distribution in distributions:
		typing_illusion(bot, userId)
		send_msg(bot, userId, lang("sending_broadcast").format(distribution[0]))

		typing_illusion(bot, distribution[0])
		if send_msg(bot, distribution[0], lang("notify_distribution").format(int(distribution[2]), float(distribution[3]), float(distribution[6]), int(distribution[4]))):			
			pending_po = db_pending(distribution[1], distribution[5])
			if pending_po is None:
				send_msg(bot, distribution[0], lang("no_pending"))
			else:
				send_msg(bot, distribution[0], lang("has_pending").format(datetime.strftime(pending_po[4], "%b %d %H:%M:%S"), float(pending_po[3])))

			cnt += 1

	typing_illusion(bot, userId)
	send_msg(bot, userId, lang("done_broadcast").format(cnt))

@restricted
def broadcast(bot, update, args):
	userId = update.message.from_user.id
	if not args:
		typing_illusion(bot, userId)
		send_msg(bot, userId, lang("empty_broadcast"))
	else:
		members = db_members()
		msg = ' '.join(args)
		typing_illusion(bot, userId)
		send_msg(bot, userId, lang("processing_broadcast").format(msg, len(members)))

		cnt = 0
		for member in members:
			typing_illusion(bot, userId)
			send_msg(bot, userId, lang("sending_broadcast").format(member[0]))
			if send_msg(bot, member[0], lang("content_broadcast").format(msg)):
				cnt += 1

		typing_illusion(bot, userId)
		send_msg(bot, userId, lang("done_broadcast").format(cnt))

def start(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		custom_keyboard = [["/stats", "/paylist"], ["/pending", "/paid"], ["/profile", "/help"]]
		reply_markup = ReplyKeyboardMarkup(custom_keyboard, one_time_keyboard=False, resize_keyboard = True)
		send_msg_kbrd(bot, userId, lang("start_old").format(account[2]), reply_markup)
		help(bot, update)

def help(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		send_msg(bot, userId, lang("help"))

def register(bot, update, args):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		if not args:
			send_msg(bot, userId, lang("empty_register"))
		else:
			valid = raiblocks_account_validate(args[0])

			if not valid:
				send_msg(bot, userId, lang("invalid_register"))
			else:
				exist = db_account_exist(args[0])
				if exist is None:
					send_msg(bot, userId, lang("not_found_register"))
				else:	
					exist2 = db_user_exist(args[0])
					if exist2 is None:
						data = {
							'chatId': userId,
							'userId': userId,
							'accountId': exist[0]
						}
						db_register(data)

						start(bot, update)
					else:
						send_msg(bot, userId, lang("exist_register"))
	else:
		start(bot, update)

def profile(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		send_msg(bot, userId, lang("profile").format(account[2], account[3], int(account[4]), float(account[5])))

def pending(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		pending_po = db_pending(account[1], account[4])
		if pending_po is None:
			send_msg(bot, userId, lang("no_pending"))
		else:
			send_msg(bot, userId, lang("has_pending").format(datetime.strftime(pending_po[4], "%b %d %H:%M:%S"), float(pending_po[3])))

def paid(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		paids = db_paid(account[1])
		send_msg(bot, userId, lang("header_paid").format(len(paids)))

		for paid in paids:
			send_msg(bot, userId, lang("item_paid").format(datetime.strftime(paid[4], "%b %d %H:%M:%S"), float(paid[3]), paid[5]))

def stats(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		today_distributions = db_today_distributions(account[1], datetime.strftime(datetime.utcnow(), "%Y-%m-%d"))
		if len(today_distributions) < 1:
			send_msg(bot, userId, lang("no_today_distributions"))
		else:
			send_msg(bot, userId, lang("header_today_distributions").format(len(today_distributions)))

			for today_distribution in today_distributions:
				send_msg(bot, userId, lang("item_today_distributions").format(datetime.strftime(today_distribution[5], "%b %d %H:%M:%S"), int(today_distribution[2]), float(today_distribution[4]), float(today_distribution[3])))

def paylist(bot, update):
	userId = update.message.from_user.id
	typing_illusion(bot, userId)

	account = db_select_user(userId)

	if account is None:
		send_msg(bot, userId, lang("start_new"))
	else:
		json = requests.get(url='https://faucet.raiblockscommunity.net/paylist.php?acc={}&json=1'.format(xrb_address)).json()
		poolClaim = int(json['pending'][0]['pending'])
		poolMrai = int(float(json['pending'][0]['expected-pay'])) / 1000000
		threshold = int(float(json['threshold']))
		claimRate =  json['reward'] / 1000000
		top60 = "Yes"
		if poolClaim < threshold:
			top60 = "No {:,} to go".format(threshold - poolClaim)

		paylist = db_paylist(account[1])
		if paylist[0] > 0 or paylist[1] > 0 or paylist[2] > 0:
			if top60 == "Yes":
				send_msg(bot, userId, lang("top60_paylist").format(int(paylist[1]), int(paylist[2]), int(paylist[0]), float(int(paylist[1]) * claimRate)))
			else:
				send_msg(bot, userId, lang("non_top60_paylist").format(int(paylist[1]), int(paylist[2]), int(paylist[0])))

		if top60 == "Yes":
			send_msg(bot, userId, lang("top60_pool_paylist").format(poolClaim, threshold, top60, float(poolMrai), claimRate))
		else:
			send_msg(bot, userId, lang("non_top60_pool_paylist").format(poolClaim, threshold, top60, claimRate))

def error(bot, update, error):
	logger.warn('Update "%s" caused error "%s"' % (update, error))

def main():
	updater = Updater(TOKEN)
	dp = updater.dispatcher
	j = updater.job_queue
	
	dp.add_handler(CommandHandler("start", start))
	dp.add_handler(CommandHandler("help", help))
	dp.add_handler(CommandHandler("register", register, pass_args=True))
	dp.add_handler(CommandHandler("profile", profile))
	dp.add_handler(CommandHandler("pending", pending))
	dp.add_handler(CommandHandler("paid", paid))
	dp.add_handler(CommandHandler("stats", stats))
	dp.add_handler(CommandHandler("paylist", paylist))
	dp.add_handler(CommandHandler("notify", notify))
	dp.add_handler(CommandHandler("broadcast", broadcast, pass_args=True))

	dp.add_error_handler(error)

	job_minute = Job(notify_distribution, 300.0)
	j.put(job_minute, next_t=0.0)

	updater.start_polling()
	updater.idle()

if __name__ == "__main__":
	print("Starting bot server")
	main()
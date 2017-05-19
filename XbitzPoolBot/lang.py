LANG = {
	"EN":{
		"start_new": "Hi! Welcome to XbitzPoolBot. \nSend:\n*/register xrb_address* \nto register.",
		"start_old": "Hi {}! \nWelcome back to XbitzPoolBot.\n",
		"help": "Here you can see your current claims, daily stats, claim reward, profile and pending payout.\n\n*/paylist* - See your current claims and pool's stats\n*/stats* - See your daily stats\n*/profile* - See your account details\n*/pending* - See your pending payout\n*/paid* - See your paid payout",
		"profile": "Account Details\nName: *{}*\nAddress: *{}*\nPayout Threshold: *{:,} Mrai (XRB)*\nBalance: *{:,.6f} Mrai (XRB)*",
		"no_pending": "You have no pending payout above threshold.",
		"has_pending": "You have one pending payout above threshold\nPayout Time: *{}*\nPayout Amount: *{:,.6f} Mrai (XRB)*",
		"header_paid": "Your last {:,} payout(s) details",
		"item_paid": "Payout Time: *{}*\nPayout Amount: *{:,.6f} Mrai (XRB)*\nPayout Hash: [hash in block explorer](https://raiblockscommunity.net/block/index.php?h={})",
		"no_today_distributions": "No distribution for you today.",
		"header_today_distributions": "Today's last {:,} distribution(s) details",
		"item_today_distributions": "Distribution Time: *{}*\nClaims: *{:,}*\nReward: *{:,.6f} Mrai (XRB)*\nClaim Rate: *{:.6f} Mrai (XRB)*",
		"non_top60_paylist": "Valid Claims: *{:,}*\nInvalid Claims: *{:,}*\nPending Claims: *{:,}*",
		"top60_paylist": "Valid Claims: *{:,}*\nInvalid Claims: *{:,}*\nPending Claims: *{:,}*\nYour Reward: *{:,.6f} Mrai (XRB)*",
		"non_top60_pool_paylist": "Pools Claims: *{:,}*\nThreshold: *{:,}\n*Top 60: *{}*\nClaim Rate: *{:.6f} Mrai (XRB)*",
		"top60_pool_paylist": "Pools Claims: *{:,}*\nThreshold: *{:,}\n*Top 60: *{}*\nPools Reward: *{:,.6f} Mrai (XRB)*\nClaim Rate: *{:.6f} Mrai (XRB)*",
		"notify_distribution": "You made *{:,} claims* this hour.\nReward: *{:,.6f} Mrai (XRB)*\nClaim Reward: *{:.6f} Mrai (XRB)*\nCurrent Balance: *{:,.6f} Mrai (XRB)*",
		"empty_broadcast": "You can't broadcast an empty message.",
		"processing_broadcast": "Sending message *{}* to *{:,}* member(s)",
		"sending_broadcast": "Sending message to *{}*",
		"content_broadcast": "Message from Admin\n\n{}",
		"done_broadcast": "Sent to *{:,}* member(s)",
		"empty_register": "Please specify an address.",
		"invalid_register": "Please specify a valid address.",
		"exist_register": "This address already in use, please use another address.",
		"not_found_register": "Please register [here](http://xbitzpool.com/login) before using this bot."
	}
}

def lang(key):
	ret = None
	
	if key in LANG["EN"]:
		ret = LANG["EN"][key]

	return ret
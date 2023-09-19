<?php

/***********************************
 * MEMBER 
************************************/

// Adm/member/member.php
define("M_MANAGER", "Manager");
define("M_MANAGEMENT", "Management");
define("M_SEARCH", "Search");
define("M_DUPE", "Overlap");
define("M_MEMBER", "Member");
define("M_SIGN", "Signup");
define("M_DOWNLOAD", "Download");
define("M_OPTION", "옵션");
define("M_WATCHLIST", "Watchlist");
define("M_AUDIT", "Audit Trail");
define("M_EXCHANGE", "Exchange");
define("M_PRIVILEGE", "Authority");
define("M_NO", "No");
define("M_CONTENT", "Content");
define("M_ID", "ID");
define("M_LANG", "Language");
define("M_IP", "IP");
define("M_LEVEL", "Level");
define("M_CURRENT", "Present");
define("M_NAME", "Name");
define("M_PHONE", "Phone");
define("M_AUTH", "Authentication");
define("M_DATE", "Date");
define("M_DATE1", "Date");
define("M_DEL", "Delete");
define("M_DEL_STATUS", "DeleteStatus");
define("M_DEL_YES", "Deleted");
define("M_DEL_NO", "Not Deleted");
define("M_STATUS", "Status");
define("M_TRADE_STATUS", "Trade");
define("M_FUND_STATUS", "Fund");
define("M_MAIL_AUTH", "Mail Authentication");
define("M_ADMIN", "Admin");
define("M_COIN", "Coin");
define("M_MARKET", "Market");
define("M_TYPE", "Type");
define("M_ALTCOIN", "Alt");
define("M_ALTCOIN2", "Alt2");
define("M_ETH", "Eth");
define("M_TOKEN", "Token");
define("M_RIPPLE", "Ripple");
define("M_EOS", "Eos");
define("M_TRON", "Tron");
define("M_TIME", "Time");
define("M_ASKPRICE", "AskPrice");
define("M_RANK", "Rank");
define("M_ISSUE", "Issue");
define("M_YEAR", "Year");
define("M_SITE", "Site");
define("M_WHITEPAPTER", "Whitepaper");
define("M_INTRO", "Introduce");
define("M_REASON", "Reason");
define("M_DEPWITH", "Dep&WithDrawl");
define("M_STOP", "Stop");
define("M_NORMAL", "Use");
define("M_TRADING", "Trading");
define("M_EXCHANGE1", "Exchange");

define("M_SIGN_DATE", M_SIGN.M_DATE);
define("M_REGISTER", M_MEMBER.M_SIGN);
define("M_REGISTRATION", "Registration");

define("M_KRW", "PHP");
define("M_DEPOSIT", "Deposit");
define("M_DAYLY", "Daily");
define("M_LIMIT", "Limit");
define("M_WITHDRAW", "Withdrawl");
define("M_DEPOSITOR", "Depositor");
define("M_DONE", "Complete");
define("M_BUY", "Buy");
define("M_SELL", "Sell");
define("M_HIS", "History");
define("M_ORDER", "Order");
define("M_ORDERER", "Orderer");
define("M_REFUND", "Refund");
define("M_PRICE", "Price");
define("M_TOTAL", "Total");
define("M_ALL", "All");
define("M_PRICE1", "Price");
define("M_AMOUNT", "Amount");
define("M_CLOSED", "Closed");
define("M_WAIT", "Wait");
define("M_PART", "Part");
define("M_CANCEL", "Cancel");
define("M_FEE", "Fee");
define("M_MIN", "Min");
define("M_MAX", "Max");
define("M_RECV", "Receive");
define("M_RECVER", "Receiver");
define("M_ACQUIRE", "Acquire");
define("M_TRANS", "Transport");

define("M_PAY", "PayMethod");
define("M_PAY_1", "Transfer");
define("M_PAY_2", "Card");
define("M_PAY_3", "Mobile");
define("M_PAY_4", "Virtual account");

define("M_AUTH_YES", "Verified");
define("M_AUTH_NO", "Unverified");

define("M_BLOCK_YES", "Block");
define("M_BLOCK_NO", "Unblock");

define("M_COUNTRY_NAME", "Country");

define("M_MANUAL", "Manual");
define("M_PROCESS", "Process");

// Adm/member/member_write.php
define("M_INPUT_ID", "Please enter your id!");
define("M_INPUT_PWD", "Please enter your password at least 4 charaters!");
define("M_PWD_CONFIRM", "Your password is incorrect.");
define("M_INPUT_NAME", "Please enter your name!");
define("M_INPUT_LEVEL", "Please enter your level!");
define("M_PWD", "Password");
define("M_CONFIRM", "Confirm");
define("M_NOT_CONFIRM", "NotConfirmed");
define("M_CONFIRM_MSG1", "Do you really want to delete?");
define("M_CONFIRM_MSG2", "Do you really want to modify?");
define("M_PWD_DESC", "Within 4~10 characters alphabets,numbers");
define("M_PWD_CONFIRM1", "Password confirmation");
define("M_PWD_CONFIRM2", "Please enter your password again.");
define("M_PWD_CONFIRM3", "Password is incorrect.");
define("M_PWD_CONFIRM4", "Please enter your new password.");
define("M_PWD_CONFIRM5", "Your new passwords are unmatched.");

define("M_NAME1", "Name");
define("M_CHANGER", "Information changer");
define("M_MAIL", "E-mail");
define("M_COUNTRY", "Country code");

define("M_ADMIN_MEMO", "Admin memo");
define("M_ACCOUNT", "Account");
define("M_SETTING", "Setting");
define("M_ENV", "Environment");
define("M_COMPANY", "Company");
define("M_ACCOUNT_OWNER", "Account owner");

define("M_BANK", "Bank name");
define("M_BIRTH", "Birth date");
define("M_REFER", "Referrer");

define("M_TRADE0", "Limits");
define("M_TRADE1", "Market price");

define("M_PROPOSED", "Proposed");
define("M_REQUEST", "Request");
define("M_APPLY", "Apply");
define("M_APPLY_NO", "Not applied");
define("M_APPLY_YES", "Applied");
define("M_PAYMENT", "Payment");
define("M_PAY_YES", "Paid");
define("M_PAY_NO", "Unpaid");
define("M_MEMO", "MEMO");
define("M_USE", "Use");
define("M_USING", "Using");
define("M_USAGE", "Usage");
define("M_REST", "Rest");
define("M_USE_YES", "Use");
define("M_USE_NO", "Not use");
define("M_DIVISION", "Division");
define("M_ORDER_COST", "Amount ordered");
define("M_CLOSE_COST", "Closed ordered");
//define("M_REGISTER", "REGISTER");

//define("M_DEL", "DELETE");

// top menu
define("M_OP_MANAGEMENT", "Operation");
define("M_MEM_MANAGEMENT", M_MEMBER);
define("M_TRADING_MANAGEMENT", "Trading");
define("M_DASHBOARD", "Dashboard");
define("M_INOUT_MANAGEMENT", "Deposit/Withdrawal");
define("M_MASTER_ACCOUNT", "MasterAccount");
define("M_LOGOUT", "Logout");

// left menu - member
define("M_MEMBER_TRACE", "Member Activity");
define("M_MEMBER_AUTH_HIST", "MemberVerification");
define("M_LOG_HIST", "Login History");
define("M_MANAGER_LOG", "Manager History");
define("M_MEMBANK_STATUS", "MemberAssetStatus");

//top menu - graph
define("M_GRAPH_BUY", "Market Buy Analytics");
define("M_GRAPH_SELL", "Market Sell Analytics");

// left menu - op
define("M_OP_ACCOUNT", "CompanyAccount");
define("M_OP_LEVEL_LIMIT", "Deposit/WithDrawl Limit");
define("M_OP_EXCHANGE", "ExchangePreferences");
define("M_OP_FEE", "FeeInquiry");
define("M_OP_NEW", "NewRegistration");

// left menu - order
define("M_ORDER_WAIT", "OrderWaitHistory");
define("M_ORDER_BUYSELL_HIST", " Buy/Sell History");

define("M_MODIFICATION", "Modification");
define("M_BACK", "Go Back");
define("M_VARIANCE", "Variance");
define("M_FILE", "File");
define("M_EXIST", "Exist");
define("M_AUTH_METHOD", "AuthMethod");
define("M_PROOF", "Proof");
define("M_ADDRESS", "Address");
define("M_MSG", "Message");
define("M_RESULT", "Result");
define("M_CODE", "Code");

define("M_LOGIN_OK", "Login Succeed(ID/PW)");
define("M_OTP_OK", "Login(OTP)");
define("M_WRONG_ID", "ID failed");
define("M_WRONG_PWD", "Password failed");
define("M_NOT_VERIFIED", "Verification failed");
define("M_NOT_OTP", "OTP failed");
define("M_CATEGORY", "Category");
define("M_LAST", "Last");
define("M_NEXTPAGE", "NextPage");
define("M_FIRST", "First");
define("M_PREVPAGE", "PrevPage");
define("M_EXELDOWN", "DownloadExel");
define("M_MONTH", "Month");
define("M_DAY", "Day");
define("M_EMAIL", "Email");
define("M_WALLET", "Wallet");
define("M_PENDING", "Pending");

define("M_SMS_REQ", "Request Code(SMS)");
define("M_SMS_OK", "Login(SMS)");
define("M_NOT_SMS", " Login failed(SMS)");

?>
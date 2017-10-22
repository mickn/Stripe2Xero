# Stripe2Xero
A tool to automatically convert Stripe's transactions to a format that Xero can import.


# How it works
The script gets all transfers after *$transfer_id*, and adds both the transfers as the individual transactions to your Xero account. Based on the Transfer IDs, Xero should be able to match Stripe's transfers to your bank account to the individual transactions.



# Released under MIT License

Copyright (c) 2016 Mick Niepoth

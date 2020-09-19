<?php
// Stripe singleton
require(APPPATH . 'core/stripe/stripe/Stripe.php');

// Utilities
require(APPPATH . 'core/stripe/stripe/Util/AutoPagingIterator.php');
require(APPPATH . 'core/stripe/stripe/Util/CaseInsensitiveArray.php');
require(APPPATH . 'core/stripe/stripe/Util/LoggerInterface.php');
require(APPPATH . 'core/stripe/stripe/Util/DefaultLogger.php');
require(APPPATH . 'core/stripe/stripe/Util/RandomGenerator.php');
require(APPPATH . 'core/stripe/stripe/Util/RequestOptions.php');
require(APPPATH . 'core/stripe/stripe/Util/Set.php');
require(APPPATH . 'core/stripe/stripe/Util/Util.php');

// HttpClient
require(APPPATH . 'core/stripe/stripe/HttpClient/ClientInterface.php');
require(APPPATH . 'core/stripe/stripe/HttpClient/CurlClient.php');

// Errors
require(APPPATH . 'core/stripe/stripe/Error/Base.php');
require(APPPATH . 'core/stripe/stripe/Error/Api.php');
require(APPPATH . 'core/stripe/stripe/Error/ApiConnection.php');
require(APPPATH . 'core/stripe/stripe/Error/Authentication.php');
require(APPPATH . 'core/stripe/stripe/Error/Card.php');
require(APPPATH . 'core/stripe/stripe/Error/Idempotency.php');
require(APPPATH . 'core/stripe/stripe/Error/InvalidRequest.php');
require(APPPATH . 'core/stripe/stripe/Error/Permission.php');
require(APPPATH . 'core/stripe/stripe/Error/RateLimit.php');
require(APPPATH . 'core/stripe/stripe/Error/SignatureVerification.php');

// OAuth errors
require(APPPATH . 'core/stripe/stripe/Error/OAuth/OAuthBase.php');
require(APPPATH . 'core/stripe/stripe/Error/OAuth/InvalidClient.php');
require(APPPATH . 'core/stripe/stripe/Error/OAuth/InvalidGrant.php');
require(APPPATH . 'core/stripe/stripe/Error/OAuth/InvalidRequest.php');
require(APPPATH . 'core/stripe/stripe/Error/OAuth/InvalidScope.php');
require(APPPATH . 'core/stripe/stripe/Error/OAuth/UnsupportedGrantType.php');
require(APPPATH . 'core/stripe/stripe/Error/OAuth/UnsupportedResponseType.php');

// API operations
require(APPPATH . 'core/stripe/stripe/ApiOperations/All.php');
require(APPPATH . 'core/stripe/stripe/ApiOperations/Create.php');
require(APPPATH . 'core/stripe/stripe/ApiOperations/Delete.php');
require(APPPATH . 'core/stripe/stripe/ApiOperations/NestedResource.php');
require(APPPATH . 'core/stripe/stripe/ApiOperations/Request.php');
require(APPPATH . 'core/stripe/stripe/ApiOperations/Retrieve.php');
require(APPPATH . 'core/stripe/stripe/ApiOperations/Update.php');

// Plumbing
require(APPPATH . 'core/stripe/stripe/ApiResponse.php');
require(APPPATH . 'core/stripe/stripe/StripeObject.php');
require(APPPATH . 'core/stripe/stripe/ApiRequestor.php');
require(APPPATH . 'core/stripe/stripe/ApiResource.php');
require(APPPATH . 'core/stripe/stripe/SingletonApiResource.php');

// Stripe API Resources
require(APPPATH . 'core/stripe/stripe/Account.php');
require(APPPATH . 'core/stripe/stripe/AlipayAccount.php');
require(APPPATH . 'core/stripe/stripe/ApplePayDomain.php');
require(APPPATH . 'core/stripe/stripe/ApplicationFee.php');
require(APPPATH . 'core/stripe/stripe/ApplicationFeeRefund.php');
require(APPPATH . 'core/stripe/stripe/Balance.php');
require(APPPATH . 'core/stripe/stripe/BalanceTransaction.php');
require(APPPATH . 'core/stripe/stripe/BankAccount.php');
require(APPPATH . 'core/stripe/stripe/BitcoinReceiver.php');
require(APPPATH . 'core/stripe/stripe/BitcoinTransaction.php');
require(APPPATH . 'core/stripe/stripe/Card.php');
require(APPPATH . 'core/stripe/stripe/Charge.php');
require(APPPATH . 'core/stripe/stripe/Collection.php');
require(APPPATH . 'core/stripe/stripe/CountrySpec.php');
require(APPPATH . 'core/stripe/stripe/Coupon.php');
require(APPPATH . 'core/stripe/stripe/Customer.php');
require(APPPATH . 'core/stripe/stripe/Discount.php');
require(APPPATH . 'core/stripe/stripe/Dispute.php');
require(APPPATH . 'core/stripe/stripe/EphemeralKey.php');
require(APPPATH . 'core/stripe/stripe/Event.php');
require(APPPATH . 'core/stripe/stripe/ExchangeRate.php');
require(APPPATH . 'core/stripe/stripe/File.php');
require(APPPATH . 'core/stripe/stripe/FileLink.php');
require(APPPATH . 'core/stripe/stripe/FileUpload.php');
require(APPPATH . 'core/stripe/stripe/Invoice.php');
require(APPPATH . 'core/stripe/stripe/InvoiceItem.php');
require(APPPATH . 'core/stripe/stripe/InvoiceLineItem.php');
require(APPPATH . 'core/stripe/stripe/IssuerFraudRecord.php');
require(APPPATH . 'core/stripe/stripe/Issuing/Authorization.php');
require(APPPATH . 'core/stripe/stripe/Issuing/Card.php');
require(APPPATH . 'core/stripe/stripe/Issuing/CardDetails.php');
require(APPPATH . 'core/stripe/stripe/Issuing/Cardholder.php');
require(APPPATH . 'core/stripe/stripe/Issuing/Dispute.php');
require(APPPATH . 'core/stripe/stripe/Issuing/Transaction.php');
require(APPPATH . 'core/stripe/stripe/LoginLink.php');
require(APPPATH . 'core/stripe/stripe/Order.php');
require(APPPATH . 'core/stripe/stripe/OrderItem.php');
require(APPPATH . 'core/stripe/stripe/OrderReturn.php');
require(APPPATH . 'core/stripe/stripe/PaymentIntent.php');
require(APPPATH . 'core/stripe/stripe/Payout.php');
require(APPPATH . 'core/stripe/stripe/Plan.php');
require(APPPATH . 'core/stripe/stripe/Product.php');
require(APPPATH . 'core/stripe/stripe/Recipient.php');
require(APPPATH . 'core/stripe/stripe/RecipientTransfer.php');
require(APPPATH . 'core/stripe/stripe/Refund.php');
require(APPPATH . 'core/stripe/stripe/Reporting/ReportRun.php');
require(APPPATH . 'core/stripe/stripe/Reporting/ReportType.php');
require(APPPATH . 'core/stripe/stripe/SKU.php');
require(APPPATH . 'core/stripe/stripe/Sigma/ScheduledQueryRun.php');
require(APPPATH . 'core/stripe/stripe/Source.php');
require(APPPATH . 'core/stripe/stripe/SourceTransaction.php');
require(APPPATH . 'core/stripe/stripe/Subscription.php');
require(APPPATH . 'core/stripe/stripe/SubscriptionItem.php');
require(APPPATH . 'core/stripe/stripe/Terminal/ConnectionToken.php');
require(APPPATH . 'core/stripe/stripe/Terminal/Location.php');
require(APPPATH . 'core/stripe/stripe/Terminal/Reader.php');
require(APPPATH . 'core/stripe/stripe/ThreeDSecure.php');
require(APPPATH . 'core/stripe/stripe/Token.php');
require(APPPATH . 'core/stripe/stripe/Topup.php');
require(APPPATH . 'core/stripe/stripe/Transfer.php');
require(APPPATH . 'core/stripe/stripe/TransferReversal.php');
require(APPPATH . 'core/stripe/stripe/UsageRecord.php');
require(APPPATH . 'core/stripe/stripe/UsageRecordSummary.php');

// OAuth
require(APPPATH . 'core/stripe/stripe/OAuth.php');

// Webhooks
require(APPPATH . 'core/stripe/stripe/Webhook.php');
require(APPPATH . 'core/stripe/stripe/WebhookSignature.php');


class Stripe {
    
}

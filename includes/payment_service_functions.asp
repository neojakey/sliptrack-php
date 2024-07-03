<%
' ###### MUST HAVE FUNCTIONS.ASP INCLUDED IN FILES USING THIS ##########

' These functions interface with calcutrack-payments, the source of truth for membership information.
' The functions that return data usually return data as a JsonObject.

' Register the customer with Stripe. Stripe requires a name and an email, but no uniqueness is enforced.
FUNCTION RegisterStripeCustomer(userId, name, email)
	Response.LCID = 1033
    Dim URI : URI = PAYMENTS_ROOT_URL & "customer"
	Dim jsonRequest : jsonRequest = "{""userId"": " & userId & ", ""name"": """ & name & """, ""email"": """ & email & """}"

    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "POST", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send jsonRequest
	
	' TODO: ERROR HANDLING

    Set srvXmlHttp = Nothing
END FUNCTION

' Delete the customer. Any active subscriptions are cancelled.
FUNCTION DeleteCustomer(userId)
	Response.LCID = 1033
    Dim URI : URI = PAYMENTS_ROOT_URL & "customer/" & userId & "?gdprErase=true"
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "DELETE", URI, False
    srvXmlHttp.Send jsonRequest
	
	Set srvXmlHttp = Nothing
END FUNCTION

' Gets subscription information for a comma-delimted collection of user ids. 
' Returns a json map of subscription information that is keyed by the CalcuTrack user id.
FUNCTION GetSubscriptionDataForCustomers(userIdsDelimited)
	Response.LCID = 1033
    Dim URI : URI = PAYMENTS_ROOT_URL & "subscriber/collection/" & userIdsDelimited
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
    srvXmlHttp.Send jsonRequest
	
	Set jsonObj = New JSONobject
	IF srvXmlHttp.status = 200 THEN
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
	END IF
	
	Set srvXmlHttp = Nothing
	Set GetSubscriptionDataForCustomers = jsonObj
END FUNCTION

' idemotent update of a customer resource. All fields must be supplied even if are not being changed.
FUNCTION UpdateStripeCustomer(userId, name, email)
	Response.LCID = 1033
    Dim URI : URI = PAYMENTS_ROOT_URL & "customer/" & userId
	Dim jsonRequest : jsonRequest = "{""name"": """ & name & """, ""email"": """ & email & """}"

    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "PUT", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send jsonRequest
	
	' TODO: ERROR HANDLING

    Set srvXmlHttp = Nothing
END FUNCTION

' Idempotent update of a local (non revenue-generating) subscription. 
' All fields must be supplied even if they are not being updated.
FUNCTION UpdateLocalSubscription(userId, product, priceInterval)
	Response.LCID = 1033
    Dim URI : URI = PAYMENTS_ROOT_URL & "admin/subscription/" & userId
	Dim jsonRequest : jsonRequest = "{""product"": """ & product & """, ""priceInterval"": """ & priceInterval & """}"    

    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "PUT", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send jsonRequest
	
	Set srvXmlHttp = Nothing
END FUNCTION

' Gets a subscriber resource which is a relationship between a customer and a price/product
' Nothing is returned unless this is a 2xx response.
FUNCTION GetSubscriber(userId)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "subscriber/" & userId
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	
	Dim jsonObj : Set jsonObj = New JSONobject
	IF srvXmlHttp.status = 200 THEN
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
	END IF
	
	Set GetSubscriber = jsonObj
	Set srvXmlHttp = Nothing
END FUNCTION

' Refreshes all session variables with up-to-date subscription information
' In order to prevent regressions across the platform, session vaiables used by the PaymentTier model
' are populated as well. 
' 
' Additionally, subscription status information is populated. 
'
' This is the best way to refresh a customer's subscription information after updating subscription information
' without having to log out/in.
FUNCTION LoadProfileForCustomer(userId)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "subscriber/" & userId
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	
	IF srvXmlHttp.status = 200 THEN
		Dim jsonObj : Set jsonObj = New JSONobject
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
		$_SESSION["userProfileLevel") = jsonObj.value("product")
		$_SESSION["userProfileStatus") = jsonObj.value("subscriptionStatus")
		' ### ACTIVE statuses
		' Incomplete - First 24 hours after subscription creation
		' past_due - Renewal failed but is not canceled yet
		IF $_SESSION["userProfileStatus") = "active" OR _
		  $_SESSION["userProfileStatus") = "trialing" OR _ 
		  $_SESSION["userProfileStatus") = "incomplete" OR _ 
		  $_SESSION["userProfileStatus") = "past_due" THEN 
			IF jsonObj.value("product") = "personal" THEN
				$_SESSION["userPaymentTierId")   = 2
				$_SESSION["userPaymentTierCode") = "Personal"
				$_SESSION["userPaymentTierType") = "PER"
			ELSEIF jsonObj.value("product") = "professional" THEN
				$_SESSION["userPaymentTierId")   = 4
				$_SESSION["userPaymentTierCode") = "Professional"
				$_SESSION["userPaymentTierType") = "PRO"
			ELSE
				$_SESSION["userPaymentTierId")   = 1
				$_SESSION["userPaymentTierCode") = "Free"
				$_SESSION["userPaymentTierType") = "FRE"
			END IF
		' ### TERMINAL INACTIVE statuses
		' Canceled - This shouldn't happen - payments-service filters these out
		' Incomplete_explired - Initial subscription invoice failed > 24 hours ago
		' Unpaid - Not enabled in Stripe settings but good to add it
		ELSEIF $_SESSION["userProfileStatus") = "canceled" OR _ 
		  $_SESSION["userProfileStatus") = "incomplete_expired" OR _ 
		  $_SESSION["userProfileStatus") = "unpaid" THEN 
			$_SESSION["userPaymentTierId")   = 1
			$_SESSION["userPaymentTierCode") = "Free"
			$_SESSION["userPaymentTierType") = "FRE"
		END IF
		
		$_SESSION["isLocal") = jsonObj.value("isLocalOnly")
	END IF
	
	Set srvXmlHttp = Nothing
END FUNCTION

' Gets products mapped by "alias". An alias is basically the primary identifier of a product. They are: "free", "personal" and "professional"
'
' param includeFree: boolean whether to include the free product or not
FUNCTION GetProducts(includeFree)
	Response.LCID = 1033
	Dim URI : URI = ""
	IF includeFree THEN
		URI = PAYMENTS_ROOT_URL & "product/?includeLocal=true"
	ELSE
		URI = PAYMENTS_ROOT_URL & "product/"
	END IF
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	
	Set jsonObj = New JSONobject
	IF srvXmlHttp.status = 200 THEN
		Set jsonObj = New JSONobject
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
	END IF
	
	Set srvXmlHttp = Nothing
	Set GetProducts = jsonObj
END FUNCTION

' Gets the products but maps it into a JSON array
'
' param includeFree: boolean whether to include the free product or not
FUNCTION GetProductsAsArray(includeFree)
	Dim products : Set products = GetProducts(includeFree)
	
	Set JSONarr = New JSONarray
	If includeFree THEN
		JSONarr.Push products.value("free")
	END IF
	JSONarr.Push products.value("personal")
	JSONarr.Push products.value("professional")
	
	Set srvXmlHttp = Nothing
	Set GetProductsAsArray = JSONarr
END FUNCTION

' Get the Stripe public key for the environment
' This has to be called to generate a Checkout Session
'
' param mode: Test or production
FUNCTION GetStripeKey(mode)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL + "key/" & mode
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	
	Dim stripeKey : stripeKey = ""
	Set jsonObj = New JSONobject
	IF srvXmlHttp.status = 200 THEN
		Set jsonObj = New JSONobject
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
		stripeKey = jsonObj.value("key")
	END IF
	
	Set srvXmlHttp = Nothing
	GetStripeKey = stripeKey
END FUNCTION

' Creates a Checkout Session
'
' Returns a session id String to be used by the Stripe JS SDK to redirect the customer to a Stripe-hosted page where they can 
' purchase a membership
FUNCTION CreateCheckoutSession(stripePriceId)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "session"
	
	Dim sessionRequest : sessionRequest = "{ ""userId"": """ & $_SESSION["userId") & """, ""priceId"": """ & stripePriceId & """}"
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "POST", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send sessionRequest
	
	Dim stripeSessionId : stripeSessionId = ""
	Set jsonObj = New JSONobject
	IF srvXmlHttp.status = 201 THEN
		Set jsonObj = New JSONobject
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
		stripeSessionId = jsonObj.value("sessionId")
	END IF
	
	Set srvXmlHttp = Nothing
	CreateCheckoutSession = stripeSessionId
END FUNCTION

' Gets a billing portal link for a customer. This link can be used to direct a customer to their billing/customer portal
' where they can manage their Stripe subscription
FUNCTION GetBillingPortalLink()
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "session/billing/" & $_SESSION["userId")
		
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "POST", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	
	Dim billingUrl : billingUrl = ""
	IF srvXmlHttp.status = 200 THEN
		Set jsonObj = New JSONobject
		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
		billingUrl = jsonObj.value("billingUrl")
	END IF
	
	Set srvXmlHttp = Nothing
	GetBillingPortalLink = billingUrl
END FUNCTION

' Get the subscriber statistics. That is, all the subsctibers at various levels of membership.
'
' Returns a json map of subscriber counts keyed by product alias
FUNCTION GetSubscriberStatistics()
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "subscriber/statistics"
	
	Set jsonObj = New JSONobject
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
	
	Set GetSubscriberStatistics = jsonOutput
END FUNCTION

' Get all the asynchronous price change migrations 
FUNCTION GetPriceJobMigrations()
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "price/migration/"
	
	Set jsonObj = New JSONobject
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "GET", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send
	Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
	
	Set srvXmlHttp = Nothing
	Set GetPriceJobMigrations = jsonOutput
END FUNCTION

' Idempotent update of a product. identified by the productAlias
FUNCTION UpdateProduct(productAlias, name, description)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "product/" & productAlias
	
	Dim productUpdateRequest : productUpdateRequest = "{ ""name"": """ & name & """, ""description"": """ & description & """}"
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "PUT", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send productUpdateRequest
	
	Set srvXmlHttp = Nothing
END FUNCTION

' Idempotent update of a price identified by both the productAlias and the priceInterval (month or year).
'
' param migrateExisting: boolean to trigger a price migration from the old price to a new one
'  Stripe does not let you change a price that has subscribed users to it, so we have to:
'  Archive the old price and create a new price. 
FUNCTION UpdatePrice(productAlias, priceInterval, newPrice, migrateExisting)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "price/" & priceInterval
	Dim priceUpdateRequest : priceUpdateRequest = "{ ""productAlias"": """ & productAlias & """, ""newIntervalPrice"": """ & newPrice & """, ""migrateActiveSubscriptions"": """ & migrateExisting & """}"
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "PUT", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send priceUpdateRequest

	Set srvXmlHttp = Nothing
END FUNCTION

' Create a new local (non-revenue generating) subscription
' 
' param userId: the calcuTrack user id
' param product: the product alias
' param overwriteExisting: cancel any subscriptions, local or not, to create a new one. 
'	If overwriteExisting is false and the user has a subscription, a 4xx response wil be returned.
FUNCTION CreateLocalSubscription(userId, product, overwriteExisting)
	Response.LCID = 1033
	Dim URI : URI = PAYMENTS_ROOT_URL & "admin/subscription/"
	Dim subscriptionRequest : subscriptionRequest = "{ ""userId"": """ & userId & """, ""product"": """ & product & """, ""overwriteExisting"": """ & overwriteExisting & """}"
	
	Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
    srvXmlHttp.Open "POST", URI, False
	srvXmlHttp.setRequestHeader "Content-Type", "application/json; charset=UTF-8" 
    srvXmlHttp.Send subscriptionRequest
END FUNCTION

%>
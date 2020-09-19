<style type="text/css">@keyframes spin{0%{transform:rotate(0)}to{transform:rotate(1turn)}}.payment-form-wrapper{background-color:#f6f9fc;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;-ms-flex-pack:center;justify-content:center;position:relative;-ms-flex:auto;flex:auto;min-width:100%;min-height:350px;-ms-flex-align:center;align-items:center;border-radius:4px;box-shadow:0 7px 14px rgba(50,50,93,.1),0 3px 6px rgba(0,0,0,.08);padding:40px}@media (max-width:670px){.payment-form-wrapper{padding:0}}.payment-form-wrapper *{font-family:Interface,Open Sans,Segoe UI,sans-serif;font-size:15px;font-weight:500}.payment-form-wrapper form{margin:0;max-width:496px!important;padding:0 15px;position:relative;width:100%;max-width:500px;transition-property:opacity,transform;transition-duration:.35s;transition-timing-function:cubic-bezier(.165,.84,.44,1)}.payment-form-wrapper.submitting form{opacity:0;transform:scale(.9);pointer-events:none}.payment-form-wrapper form>*+*{margin-top:20px}.payment-form-wrapper .container-stripe{margin:0 auto;width:100%;max-width:1040px;background-color:#fff;box-shadow:0 4px 6px rgba(50,50,93,.11),0 1px 3px rgba(0,0,0,.08);border-radius:4px;padding-bottom:3px;box-sizing:content-box!important;position:static!important;display:block!important}.container-stripe *{box-sizing:content-box!important}.payment-form-wrapper fieldset{border-style:none;padding:7px;margin-left:-5px;margin-right:-5px;background:rgba(18,91,152,.1);border-radius:8px}.payment-form-wrapper fieldset legend{margin:0!important;float:left!important;width:100%!important;text-align:center!important;font-size:13px!important;color:#8898aa!important;padding:3px 10px 7px!important;font-weight:400!important;border-style:none!important}.payment-form-wrapper .card-only{display:block}.payment-form-wrapper .payment-request-available{display:none}.payment-form-wrapper fieldset legend+*{clear:both}.payment-form-wrapper button,.payment-form-wrapper input{-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:0;border-style:none;color:#fff;padding:0!important}.payment-form-wrapper input:-webkit-autofill{transition:background-color 100000000s;-webkit-animation:1ms void-animation-out}.payment-form-wrapper #card-element{padding:10px;margin-bottom:2px}.payment-form-wrapper input{-webkit-animation:1ms void-animation-out}.payment-form-wrapper input::-webkit-input-placeholder{color:#9bacc8}.payment-form-wrapper input::-moz-placeholder{color:#9bacc8}.payment-form-wrapper input:-ms-input-placeholder{color:#9bacc8}.payment-form-wrapper button{display:inline-block;width:48%;height:37px;background-color:#343a40;border-radius:2px;color:#fff;cursor:pointer;margin:3px}.payment-form-wrapper button:active{background-color:#b76ac4}.payment-form-wrapper .error-stripe svg .base{fill:#e25950}.payment-form-wrapper .error-stripe svg .glyph{fill:#f6f9fc}.payment-form-wrapper .error-stripe svg{-ms-flex-negative:0;flex-shrink:0;margin-top:-1px;margin-right:10px}.payment-form-wrapper .error-stripe{display:-ms-flexbox;display:flex;-ms-flex-pack:center;justify-content:center;position:absolute;width:100%;top:100%;margin-top:20px;left:0;padding:0 15px;font-size:13px!important;opacity:0;transform:translateY(10px);transition-property:opacity,transform;transition-duration:.35s;transition-timing-function:cubic-bezier(.165,.84,.44,1)}.payment-form-wrapper .error-stripe.visible{opacity:1;transform:none}.payment-form-wrapper .error-stripe .message{color:#e25950;font-size:inherit}.payment-form-wrapper .success-stripe .icon .border{stroke:#ffc7ee}.payment-form-wrapper .success-stripe .icon .checkmark{stroke:#d782d9}.payment-form-wrapper .success-stripe .title{color:#32325d}.payment-form-wrapper .success-stripe .message{color:#8898aa}.payment-form-wrapper .success-stripe .reset path{fill:#d782d9}.payment-form-wrapper .success-stripe{display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;-ms-flex-align:center;align-items:center;-ms-flex-pack:center;justify-content:center;position:absolute;width:100%;height:100%;top:0;left:0;padding:10px;text-align:center;pointer-events:none;overflow:hidden}.payment-form-wrapper .submitting .success-stripe{pointer-events:all}.payment-form-wrapper .success-stripe>*{transition-property:opacity,transform;transition-duration:.35s;transition-timing-function:cubic-bezier(.165,.84,.44,1);opacity:0;transform:translateY(50px)}.payment-form-wrapper .success-stripe .icon{margin-top:-50px;transform:translateY(70px) scale(.75)}.payment-form-wrapper .success-stripe .icon svg{will-change:transform}.payment-form-wrapper .success-stripe .icon .border{stroke:#ffc7ee;stroke-dasharray:251;stroke-dashoffset:62.75;transform-origin:50% 50%;transition:stroke-dashoffset .35s cubic-bezier(.165,.84,.44,1);animation:spin 1s linear infinite}.submitting .success-stripe .icon{pointer-events:all;opacity:1}.journal-stripe{min-height:200px!important;padding-top:0!important;margin-left:0!important;margin-right:0!important}.payment-form-wrapper .checkbox-inline input.checkbox_save_card{-webkit-appearance:checkbox}.payment-form-wrapper .checkbox-inline{margin-left:10px}.quick-checkout-wrapper .right .checkout-section.checkout-payment-details.payment-stripe{display:block!important}.quick-checkout-wrapper .right .checkout-payment-details .payment-form-wrapper legend.payment-request-available{display:none}#d_quickcheckout #payment_view .payment-form-wrapper a{display:block!important}#d_quickcheckout .payment-form-wrapper .payment-request-available{display:none}</style>

<div class="payment-form-wrapper">
    <?php if($test_mode) { ?>
        <small class="text-info">It is in test mode. Please use only valid test cards. <br> Visit: <a href="https://stripe.com/docs/testing" target="_blank">https://stripe.com/docs/testing</a>.</small>
    <?php } ?>
    <form id="payment-form">
        <div id="payment-request-button"></div>
        <fieldset>
            <legend class="card-only">Pay with card</legend>
            <legend class="payment-request-available">or Pay with card</legend>
            <div class="container-stripe">
                <div id="card-element"></div>
                <button class="btn btn-default" type="button" onclick='backCheckout()'>Back</button>
                <button type="button" id="button-confirm" class="buttons">Submit Payment</button>
            </div>
        </fieldset>
        <div class="error-stripe" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
            </svg>
            <span id="card-errors" class="message"></span>
        </div>
    </form>
    <div class="success-stripe">
        <div class="icon">
            <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink">
                <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
            </svg>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    function initStripe() {
        if (window.Stripe) {

            const stripe = Stripe('<?php echo $stripe_public_key; ?>');
            const elements = stripe.elements();

            var style = {
                base: {
                    color         : "#32325D",
                    fontWeight    : 500,
                    fontFamily    : "Inter UI, Open Sans, Segoe UI, sans-serif",
                    fontSize      : "15px",
                    fontSmoothing : "antialiased",
                    "::placeholder": {
                        color: "#CFD7DF"
                    }
                },
                invalid: {
                    color: "#E25950"
                }
            };

            const cardElement = elements.create('card', {style: style, hidePostalCode: true});
            cardElement.mount('#card-element');
            const cardButton = document.getElementById('button-confirm');
            var billing_details = <?php echo json_encode($billing_details); ?>;

            cardButton.addEventListener('click', async (ev) => {
                $('.payment-form-wrapper').addClass('submitting');
                const {paymentMethod, error} = await stripe.createPaymentMethod('card', cardElement, billing_details);
                if (error) {
                    showErrorMessage(error.message);
                } else {

                    const response = await fetch('<?php echo $action; ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ payment_method_id: paymentMethod.id })
                    });

                    const json = await response.json();
                    handleServerResponse(json);
                }
            });

            const handleServerResponse = async (response) => {
                if (response.error) {
                    showErrorMessage(response.error);
                } else if (response.requires_action) {
                    const { error: errorAction, paymentIntent } = await stripe.handleCardAction(response.payment_intent_client_secret);

                    if (errorAction) {
                        showErrorMessage(errorAction.message);
                    } else {
                        const serverResponse = await fetch('<?php echo $action; ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ payment_intent_id: paymentIntent.id })
                        });
                        handleServerResponse(await serverResponse.json());
                    }
                } else {
                    window.location = response.success;
                }
            }

            const showErrorMessage = (error) => {
                $(".payment-form-wrapper #card-errors").text(error);
                $(".payment-form-wrapper .error-stripe").addClass("visible");
                $('.payment-form-wrapper').removeClass('submitting');
            }
        } else {
            setTimeout(function() { initStripe() }, 50);
        }
    }

    initStripe();
</script>
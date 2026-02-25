firebase.initializeApp(firebaseConfig);


// firebase.analytics();

const messaging = firebase.messaging();
const base_url = window.location.host == "localhost" ? window.location.origin + "/" + window.location.pathname.split('/')[1] : window.location.origin;
navigator.serviceWorker.register(base_url + "/firebase-messaging-sw.js")
    .then((registration) => {
        messaging.useServiceWorker(registration);

        messaging.requestPermission().then(function () {
            // console.log('Notification permission granted.');
            /*messaging.onMessage((payload) => {
                console.log('Message received. ', payload);
                appendMessage(payload);
            });*/
            // TODO(developer): Retrieve an Instance ID token for use with FCM.
            // ...
        }).catch(function (err) {
            // console.log('Unable to get permission to notify.', err);
        });

        messaging.getToken().then((currentToken) => {
            if (currentToken) {
                sendTokenToServer(currentToken);
                updateUIForPushEnabled(currentToken);
            } else {
                // Show permission request.
                // console.log('No Instance ID token available. Request permission to generate one.');
                // Show permission UI.
                updateUIForPushPermissionRequired();
                setTokenSentToServer(false);
            }
        }).catch((err) => {
            // console.log('An error occurred while retrieving token. ', err);
            showToken('Error retrieving Instance ID token. ', err);
            setTokenSentToServer(false);
        });

        messaging.onTokenRefresh(() => {
            messaging.getToken().then((refreshedToken) => {

                // console.log('Token refreshed.');
                // Indicate that the new Instance ID token has not yet been sent to the
                // app server.
                setTokenSentToServer(false);
                // Send Instance ID token to app server.
                sendTokenToServer(refreshedToken);
                // [START_EXCLUDE]
                // Display new Instance ID token and clear UI of all previous messages.
                resetUI();
                // [END_EXCLUDE]
            }).catch((err) => {
                // console.log('Unable to retrieve refreshed token ', err);
                showToken('Unable to retrieve refreshed token ', err);
            });
        });

    });
messaging.onMessage(function (payload) {
    navigator.serviceWorker.ready.then(function (registration) {
        registration.showNotification(payload.notification.title, {
            body: payload.notification.body,
            icon: payload.notification.icon,
            onclick: payload.notification.click_action
        });
    });
});

function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? '1' : '0');
}

function showToken(currentToken) {
    // Show token in console and UI.
    /* var tokenElement = document.querySelector('#token');
     tokenElement.textContent = currentToken;*/
    // console.log(currentToken)
}

function isTokenSentToServer() {
    return window.localStorage.getItem('sentToServer') === '1';
}

function updateUIForPushEnabled(currentToken) {
    /*showHideDiv(tokenDivId, true);
    showHideDiv(permissionDivId, false);*/
    showToken(currentToken);
}

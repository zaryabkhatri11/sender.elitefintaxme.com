// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.
'use strict';
importScripts('https://www.gstatic.com/firebasejs/7.3.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.3.0/firebase-messaging.js');
// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
// Initialize Firebase
var firebaseConfig;
fetch('.firebaserc')
    .then(response => response.text())
    .then((text) => {
        firebaseConfig = JSON.parse(text).firebase;

        firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
        const messaging = firebase.messaging();


        messaging.setBackgroundMessageHandler(function (payload) {
            // Customize notification here
            const notificationTitle = 'Background Message Title';
            const notificationOptions = {
                body: 'Background Message body.',
                icon: '/firebase-logo.png'
            };

            return self.registration.showNotification(notificationTitle,
                notificationOptions);
        });
    })
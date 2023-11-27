// Import the functions you need from the SDKs you need
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAdM4qSbQC1ebmOJ77jTADPnNjN_2Y6kbw",
  authDomain: "blog-app-laravel.firebaseapp.com",
  projectId: "blog-app-laravel",
  storageBucket: "blog-app-laravel.appspot.com",
  messagingSenderId: "560468941993",
  appId: "1:560468941993:web:52733e57a2cbfe143a5db8",
  measurementId: "G-E4HEP83JYX"
};

// Initialize Firebase
const app = firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging(app);
messaging.setBackgroundMessageHandler((payload) => {
    console.log("Message received.", payload);

    // Customize Message
    const notificationTitle  = "Hello world is awesome";
    const notificationOptions  = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});

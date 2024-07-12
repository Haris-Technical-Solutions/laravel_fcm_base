<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    {{-- this script have some help full code to handle notification clikc events etc. 
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyA1rjB1fIS0HbkWMcW4_v1KkYiPfQtzNG8",
            authDomain: "tutorial-8a96e.firebaseapp.com",
            projectId: "tutorial-8a96e",
            storageBucket: "tutorial-8a96e.appspot.com",
            messagingSenderId: "990004975682",
            appId: "1:990004975682:web:8d068d227cc6efa4d7f5db",
            measurementId: "G-3H4W9XEYP7"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);

        // Initialize Firebase Cloud Messaging
        const messaging = getMessaging(app);
        const vapidKey = "BBbghCCpcW_DxTpZbqtcAIPJwkkMoA8VlNzYfv8fU-C1HWfDCuz3zneCQmTkbanKoEpp3vUMcdEXz58xVtd024o";

        async function registerServiceWorker() {
            try {
                const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js', { type: 'module' });
                console.log('Service worker registration successful:', registration);

                const currentToken = await getToken(messaging, {
                    serviceWorkerRegistration: registration,
                    vapidKey: vapidKey
                });

                if (currentToken) {
                    console.log(currentToken);

                    await fetch("{{url('/store_fcm')}}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-Token": "{{csrf_token()}}"
                        },
                        body: JSON.stringify({
                            fcm_token: currentToken
                        })
                    });

                    console.log('Token sent to server');
                } else {
                    console.log('No registration token available. Request permission to generate one.');
                }
            } catch (err) {
                console.error('An error occurred during service worker registration or token retrieval:', err);
            }
        }

        $(document).ready(function(){
            registerServiceWorker();

            // Listen for messages when the web app is in the foreground
            onMessage(messaging, (payload) => {
                console.log('Message received. ', payload);
                // Show notification to the user (you can customize this part)
                new Notification(payload.notification.title, payload.notification);
            });

            // Periodically refresh the token
            // setInterval(registerServiceWorker, 300000); // Refresh every 5 minutes
        });

        console.log(app, analytics, messaging);
    </script> --}}

    <script type="module">


        
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

        // firebase service worker
        // import "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-sw.js";

        // import { } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-push-scope.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries
    
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional


        const firebaseConfig = {
            apiKey: "",
            authDomain: "",
            projectId: "",
            storageBucket: "",
            messagingSenderId: "",
            appId: "",
            measurementId: ""
        };
    
        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);

        // Initialize Firebase Cloud Messaging and get a reference to the service
        const messaging = getMessaging(app);

        console.log(messaging,app);
        const vapidKey = ""

        
        
        // var registration = navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' });navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' })
        // var registration;
        function service_worker(){
            if(true){
                navigator.serviceWorker.register('/firebase-messaging-sw.js', { type: 'module' })
                .then(function(registration) {
                    // console.log('Service worker registration successful:', registration);
                    getToken(messaging,{ 
                        serviceWorkerRegistration: registration,
                        vapidKey: vapidKey
                    }).then((currentToken) => {
                        if (currentToken) {
                            // Send the token to your server and update the UI if necessary
                            // ...
                            console.log(currentToken);
                            $.post("{{url('/store_fcm')}}",{
                                '_token':'{{csrf_token()}}',
                                'fcm_token':currentToken,
                            }).then(function(resp){
                                console.log(resp);
                            });

                        } else {
                            // Show permission request UI
                            console.log('No registration token available. Request permission to generate one.');
                            // ...
                        }
                    }).catch((err) => {
                        console.log('An error occurred while retrieving token. ', err);
                        // ...
                    });
                    // Call useServiceWorker() here using the registration object
                })
                .catch(function(error) {
                    console.error('Service worker registration failed:', error);
                });
            }
        }
        $(document).ready(function(){
            // service_worker()
            setInterval(service_worker(), 300000);
        })


        // console.log(registration);
        

        console.log(app, analytics, messaging   )
    </script> 

</html>

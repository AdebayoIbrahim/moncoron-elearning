// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true,
//     forceTLS: true,
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     wssPort: 6001,
//     disableStats: true,
// });

// const configuration = {
//     iceServers: [
//         {
//             urls: 'stun:stun.l.google.com:19302',
//         },
//     ],
// };

// const peerConnections = {};  // Object to store peer connections

// window.Echo.join('presence-video-channel')
//     .here(users => {
//         console.log('Users currently in the channel:', users);
//     })
//     .joining(user => {
//         console.log('User joined:', user);
//     })
//     .leaving(user => {
//         console.log('User left:', user);
//     })
//     .listen('StartVideoChat', (e) => {  // Make sure the event name matches exactly with your Laravel event
//         console.log('StartVideoChat event received:', e);
//         if (e.data.type === 'incomingCall') {
//             handleIncomingCall(e.data);
//         } else if (e.data.type === 'callAccepted') {
//             handleCallAccepted(e.data);
//         } else if (e.data.type === 'signal') {
//             handleSignal(e.data);
//         }
//     });

// function handleIncomingCall(data) {
//     const peerConnection = new RTCPeerConnection(configuration);
//     peerConnections[data.from] = peerConnection;

//     peerConnection.setRemoteDescription(new RTCSessionDescription(data.signalData));
//     peerConnection.createAnswer().then(answer => {
//         peerConnection.setLocalDescription(answer);
//         axios.post('/video-chat/accept', { signal: answer, to: data.from });
//     });

//     peerConnection.ontrack = event => {
//         const remoteVideo = document.getElementById('remoteVideo');
//         remoteVideo.srcObject = event.streams[0];
//     };

//     console.log('Incoming call handled');
// }

// function handleCallAccepted(data) {
//     const peerConnection = peerConnections[data.to];
//     peerConnection.setRemoteDescription(new RTCSessionDescription(data.signal));

//     console.log('Call accepted and signal handled');
// }

// function handleSignal(data) {
//     const peerConnection = peerConnections[data.to];
//     peerConnection.addIceCandidate(new RTCIceCandidate(data.signal.candidate));

//     console.log('Signal data handled');
// }

// function callUser(userToCall) {
//     const peerConnection = new RTCPeerConnection(configuration);
//     peerConnections[userToCall] = peerConnection;

//     const localVideo = document.getElementById('localVideo');
//     const localStream = localVideo.srcObject;
//     localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

//     peerConnection.createOffer().then(offer => {
//         peerConnection.setLocalDescription(offer);
//         axios.post('/video-chat/call', { user_to_call: userToCall, signal_data: offer });
//     });

//     peerConnection.ontrack = event => {
//         const remoteVideo = document.getElementById('remoteVideo');
//         remoteVideo.srcObject = event.streams[0];
//     };

//     console.log('Call initiated');
// }

// navigator.mediaDevices.getUserMedia({ video: true, audio: true })
//     .then(stream => {
//         const localVideo = document.getElementById('localVideo');
//         localVideo.srcObject = stream;
//     })
//     .catch(error => {
//         console.error('Error accessing media devices.', error);
//     });

// document.getElementById('callButton').addEventListener('click', () => {
//     const userToCall = prompt('Enter the user ID to call:');
//     if (userToCall) {
//         callUser(userToCall);
//     }
// });

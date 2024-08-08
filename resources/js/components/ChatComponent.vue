<template>
    <div>
      <h2>Welcome to the Classroom</h2>
      <p>Current Route: {{ routeNamePart }}</p>
      <!-- Chat message list -->
      <ul id="messages">
        <li v-for="message in messages" :key="message.id">
          {{ message.user.name }}: {{ message.message }}
          <template v-if="message.file_type">
            <img v-if="message.file_type === 'image'" :src="getFilePath(message.file_path)" alt="Image" style="max-width: 200px;">
            <video v-if="message.file_type === 'video'" :src="getFilePath(message.file_path)" controls style="max-width: 200px;"></video>
            <audio v-if="message.file_type === 'audio'" :src="getFilePath(message.file_path)" controls></audio>
            <a v-else :href="getFilePath(message.file_path)" download>Download File</a>
          </template>
        </li>
      </ul>
      <!-- Message form -->
      <form @submit.prevent="sendMessage">
        <input type="text" v-model="newMessage" placeholder="Type a message" required>
        <input type="file" @change="handleFileUpload">
        <button type="submit" class="btn btn-primary">Send</button>
        <button type="button" @click="toggleAudioRecord" class="btn btn-secondary">
          <img :src="audioRecording ? stopIcon : microphoneIcon" alt="Audio" style="width: 24px; height: 24px;">
        </button>
        <button type="button" @click="toggleVideoRecord" class="btn btn-secondary">
          <img :src="videoRecording ? stopIcon : videoCameraIcon" alt="Video" style="width: 24px; height: 24px;">
        </button>
      </form>
      <div id="recordings-preview" style="margin-top: 20px;">
        <audio id="audio-preview" controls v-if="audioPreview" :src="audioPreview"></audio>
        <video id="video-preview" width="320" height="240" controls v-if="videoPreview" :src="videoPreview"></video>
      </div>
    </div>
  </template>
  
  <script>
  import Echo from "laravel-echo";
  
  export default {
    data() {
      return {
        messages: [],
        newMessage: '',
        file: null,
        audioRecorder: null,
        audioChunks: [],
        audioPreview: null,
        audioRecording: false,
        videoRecorder: null,
        videoChunks: [],
        videoPreview: null,
        videoRecording: false,
        microphoneIcon: require('@/assets/microphone-icon.png'),
        videoCameraIcon: require('@/assets/video-camera-icon.png'),
        stopIcon: require('@/assets/stop-icon.png'),
      };
    },
    props: {
      routeNamePart: String,
      courseId: Number,
      lessonId: Number
    },
    mounted() {
      this.initPusher();
    },
    methods: {
      initPusher() {
        const echo = new Echo({
          broadcaster: 'pusher',
          key: process.env.MIX_PUSHER_APP_KEY,
          cluster: process.env.MIX_PUSHER_APP_CLUSTER,
          forceTLS: true
        });
  
        echo.channel('chat')
          .listen('MessageSent', (e) => {
            this.messages.push(e.message);
          });
      },
      async sendMessage() {
        const formData = new FormData();
        formData.append('message', this.newMessage);
        if (this.file) formData.append('file', this.file);
        if (this.audioPreview) formData.append('audio_data', this.audioPreview.split(',')[1]);
        if (this.videoPreview) formData.append('video_data', this.videoPreview.split(',')[1]);
  
        await axios.post(`/chat/send/${this.courseId}/${this.lessonId}`, formData);
        this.newMessage = '';
        this.file = null;
        this.audioPreview = null;
        this.videoPreview = null;
      },
      handleFileUpload(event) {
        this.file = event.target.files[0];
      },
      toggleAudioRecord() {
        if (this.audioRecorder && this.audioRecorder.state === 'recording') {
          this.audioRecorder.stop();
          this.audioRecording = false;
        } else {
          this.startAudioRecording();
        }
      },
      async startAudioRecording() {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        this.audioRecorder = new MediaRecorder(stream);
        this.audioRecorder.ondataavailable = e => {
          this.audioChunks.push(e.data);
        };
        this.audioRecorder.onstop = () => {
          const blob = new Blob(this.audioChunks, { type: 'audio/webm' });
          this.audioChunks = [];
          this.audioPreview = URL.createObjectURL(blob);
        };
        this.audioRecorder.start();
        this.audioRecording = true;
      },
      toggleVideoRecord() {
        if (this.videoRecorder && this.videoRecorder.state === 'recording') {
          this.videoRecorder.stop();
          this.videoRecording = false;
        } else {
          this.startVideoRecording();
        }
      },
      async startVideoRecording() {
        const constraintObj = {
          audio: true,
          video: {
            facingMode: "user",
            width: { min: 640, ideal: 1280, max: 1920 },
            height: { min: 480, ideal: 720, max: 1080 }
          }
        };
        const stream = await navigator.mediaDevices.getUserMedia(constraintObj);
        const videoElement = document.getElementById('video');
        videoElement.srcObject = stream;
        videoElement.style.display = 'block';
  
        this.videoRecorder = new MediaRecorder(stream);
        this.videoRecorder.ondataavailable = e => {
          this.videoChunks.push(e.data);
        };
        this.videoRecorder.onstop = () => {
          const blob = new Blob(this.videoChunks, { type: 'video/webm' });
          this.videoChunks = [];
          this.videoPreview = URL.createObjectURL(blob);
          stream.getTracks().forEach(track => track.stop());
          videoElement.style.display = 'none';
        };
        this.videoRecorder.start();
        this.videoRecording = true;
      },
      getFilePath(path) {
        return `/storage/${path}`;
      }
    }
  }
  </script>
  
  <style scoped>
  /* Add any scoped CSS you need for your component */
  </style>
  
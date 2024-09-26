<template>
  <AppLayout>
    <Head title="Event Calendar" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Event Calendar</h2>
    </template>

    <main class="py-12 bg-gray-100">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <!-- Event Calendar -->
          <div class="p-4">
            <h3 class="text-base font-medium leading-6 text-gray-900 mb-3">Your Events</h3>
            <div class="sm:w-full md:w-3/4 lg:w-2/3 mx-auto">
              <FullCalendar :options="calendarOptions" />
            </div>
          </div>

          <!-- Add New Event Form (visible when formVisible is true) -->
          <div v-if="formVisible" class="border-t border-gray-200 p-4">
            <div class="flex justify-between items-center mb-4">
              <h4 class="text-base font-medium text-gray-900">Add a New Event</h4>
              <button
                @click="closeForm"
                class="bg-red-500 text-white px-2 py-1 rounded-md text-sm hover:bg-red-600 transition"
              >
                Close
              </button>
            </div>
            <form @submit.prevent="addEvent" class="space-y-3 max-w-sm mx-auto">
              <!-- Event Title -->
              <div class="mb-2">
                <label for="title" class="block text-sm font-medium text-gray-600">Event Title</label>
                <input
                  type="text"
                  v-model="eventData.title"
                  id="title"
                  class="block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-sm focus:ring focus:ring-blue-200"
                  placeholder="Enter event title"
                  required
                />
              </div>

              <!-- Event Prompt (Select) -->
              <div class="mb-2">
                <label for="prompt" class="block text-sm font-medium text-gray-600">Event Prompt</label>
                <select
                  v-model="eventData.prompt"
                  id="prompt"
                  class="block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-sm focus:ring focus:ring-blue-200"
                  @change="populateResponse"
                >
                  <option value="" disabled>Select a prompt...</option>
                  <option
                    v-for="(entry, index) in userPrompts"
                    :key="index"
                    :value="entry.prompt"
                  >
                    {{ entry.prompt }}
                  </option>
                </select>
              </div>

              <!-- Event Response -->
              <div class="mb-2">
                <label for="response" class="block text-sm font-medium text-gray-600">Event Response</label>
                <textarea
                  v-model="eventData.response"
                  id="response"
                  class="block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-sm focus:ring focus:ring-blue-200"
                  placeholder="Enter event response"
                ></textarea>
              </div>

              <!-- Start Date Picker -->
              <div class="mb-2">
                <label for="start" class="block text-sm font-medium text-gray-600">Start Date</label>
                <input
                  type="datetime-local"
                  v-model="eventData.startDate"
                  id="start"
                  class="block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-sm focus:ring focus:ring-blue-200"
                  required
                />
              </div>

              <!-- End Date Picker -->
              <div class="mb-2">
                <label for="end" class="block text-sm font-medium text-gray-600">End Date</label>
                <input
                  type="datetime-local"
                  v-model="eventData.endDate"
                  id="end"
                  class="block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-sm focus:ring focus:ring-blue-200"
                  required
                />
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-md text-sm hover:bg-blue-600 transition"
              >
                Add Event
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal for Event Details -->
      <div v-if="selectedEvent" class="fixed z-50 inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full">
          <h4 class="text-lg font-semibold mb-2">{{ selectedEvent.title }}</h4>
          <p class="text-gray-700 text-sm"><strong>Prompt:</strong> {{ selectedEvent.extendedProps.prompt }}</p>
          <p class="text-gray-700 text-sm"><strong>Response:</strong> {{ selectedEvent.extendedProps.response }}</p>
          <p class="text-gray-700 text-sm"><strong>Start:</strong> {{ new Date(selectedEvent.start).toLocaleString() }}</p>
          <p class="text-gray-700 text-sm"><strong>End:</strong> {{ new Date(selectedEvent.end).toLocaleString() }}</p>

          <!-- Delete Event Button -->
          <button
            @click="deleteEvent(selectedEvent.id)"
            class="mt-3 w-full bg-red-500 text-white py-2 rounded-md text-sm hover:bg-red-600 transition"
          >
            Delete Event
          </button>
          <button
            @click="closeEventDetails"
            class="mt-2 w-full bg-gray-500 text-white py-2 rounded-md text-sm hover:bg-gray-600 transition"
          >
            Close
          </button>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Form states
const eventData = reactive({
  title: '',
  prompt: '',
  response: '',
  startDate: '',
  endDate: '',
});
const selectedEvent = ref(null);
const formVisible = ref(false);
const userPrompts = ref([]); // Array per memorizzare i prompt e le response dell'utente

// Calendar options
const clickTimeout = ref(null);
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  dateClick: (info) => {
    if (clickTimeout.value) {
      clearTimeout(clickTimeout.value);
      clickTimeout.value = null;
      openAddEventForm(info.dateStr);
    } else {
      clickTimeout.value = setTimeout(() => {
        clickTimeout.value = null;
      }, 300);
    }
  },
  events: [],
  eventClick: (info) => {
    selectedEvent.value = info.event;
  },
});

// Function to open the form with pre-filled dates
const openAddEventForm = (dateStr) => {
  const formattedDate = new Date(dateStr).toISOString().slice(0, 16);
  eventData.startDate = formattedDate;
  eventData.endDate = formattedDate;
  formVisible.value = true;
};

// Function to close the form
const closeForm = () => {
  formVisible.value = false;
  // Reset form data
  eventData.title = '';
  eventData.prompt = '';
  eventData.response = '';
  eventData.startDate = '';
  eventData.endDate = '';
};

// Function to populate response based on selected prompt
const populateResponse = () => {
  console.log('Selected Prompt:', eventData.prompt); // Debug: Verifica quale prompt è stato selezionato
  const selectedPrompt = userPrompts.value.find((entry) => entry.prompt === eventData.prompt);
  console.log('Selected Entry:', selectedPrompt); // Debug: Controlla l'entry selezionato
  if (selectedPrompt && selectedPrompt.response) {
    // Se il response è un JSON, decodificalo
    try {
      const parsedResponse = JSON.parse(selectedPrompt.response);
      eventData.response = parsedResponse.facebook || parsedResponse.twitter || parsedResponse.tiktok || parsedResponse.instagram || '';
    } catch (error) {
      eventData.response = selectedPrompt.response;
    }
  } else {
    eventData.response = '';
  }
};

// Fetch events
const fetchEvents = async () => {
  try {
    const { data } = await axios.get('/api/events');
    calendarOptions.value.events = data.map((event) => ({
      id: event.id,
      title: event.title,
      start: event.start.split('T')[0],
      end: event.end.split('T')[0],
      extendedProps: {
        prompt: event.prompt,
        response: event.response,
      },
    }));
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

// Fetch user prompts from search history
const fetchUserPrompts = async () => {
  try {
    const { data } = await axios.get('/user-prompts');
    console.log('Received prompts:', data); // Debug: Verifica i dati ricevuti
    userPrompts.value = data;

    // Controlla se i prompt sono correttamente popolati
    userPrompts.value.forEach((entry, index) => {
      console.log(`Prompt ${index}:`, entry.prompt); // Debug: Stampa i prompt ricevuti
    });

  } catch (error) {
    console.error('Error fetching user prompts:', error.response ? error.response : error.message);
  }
};

// Add a new event
const addEvent = async () => {
  try {
    const newEvent = {
      title: eventData.title,
      prompt: eventData.prompt,
      response: eventData.response,
      start_date: new Date(eventData.startDate).toISOString(),
      end_date: new Date(eventData.endDate).toISOString(),
    };
    await axios.post('/api/events', newEvent);
    await fetchEvents();
    closeForm(); // This will reset the form and close it
  } catch (error) {
    console.error('Error adding event:', error.response ? error.response.data : error.message);
  }
};

// Delete event
const deleteEvent = async (eventId) => {
  try {
    await axios.delete(`/api/events/${eventId}`);
    await fetchEvents();
    selectedEvent.value = null;
  } catch (error) {
    console.error('Error deleting event:', error);
  }
};

// Close event details modal
const closeEventDetails = () => {
  selectedEvent.value = null;
};

// Fetch events and prompts on component mount
onMounted(() => {
  fetchEvents();
  fetchUserPrompts();
});
</script>


<style scoped>
form {
  background-color: rgba(0, 163, 224, 0.2);
  padding: 16px;
  border-radius: 8px;
}

form input,
form button {
  transition: all 0.2s ease;
}

form input:hover,
form button:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>

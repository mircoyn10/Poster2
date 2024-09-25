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
                <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
                <input
                  type="text"
                  v-model="eventTitle"
                  id="title"
                  class="block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-sm focus:ring focus:ring-blue-200"
                  placeholder="Enter event title"
                  required
                />
              </div>

              <!-- Start Date Picker -->
              <div class="mb-2">
                <label for="start" class="block text-sm font-medium text-gray-600">Start Date</label>
                <input
                  type="datetime-local"
                  v-model="eventStart"
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
                  v-model="eventEnd"
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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Form states
const eventTitle = ref('');
const eventStart = ref('');
const eventEnd = ref('');
const selectedEvent = ref(null);
const formVisible = ref(false); // Control visibility of the form

// Calendar options
const clickTimeout = ref(null);
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  dateClick: (info) => {
    if (clickTimeout.value) {
      clearTimeout(clickTimeout.value); // Double click detected, open form
      clickTimeout.value = null;
      openAddEventForm(info.dateStr); // Pass the clicked date
    } else {
      clickTimeout.value = setTimeout(() => {
        clickTimeout.value = null; // Reset if not a double click
      }, 300); // Wait for 300ms for a second click
    }
  },
  events: [],
  eventClick: (info) => {
    selectedEvent.value = info.event;
  },
});

// Function to open the form with pre-filled dates
const openAddEventForm = (dateStr) => {
  // Pre-fill the start and end date fields
  const formattedDate = new Date(dateStr).toISOString().slice(0, 16); // Format to 'YYYY-MM-DDTHH:MM'
  eventStart.value = formattedDate;
  eventEnd.value = formattedDate;
  formVisible.value = true; // Show the form
};

// Function to close the form
const closeForm = () => {
  formVisible.value = false; // Hide the form
};

// Fetch events
const fetchEvents = async () => {
  try {
    const { data } = await axios.get('/api/events');
    calendarOptions.value.events = data.map((event) => ({
      id: event.id,
      title: event.title,
      start: event.start,
      end: event.end,
    }));
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

// Add a new event
const addEvent = async () => {
  try {
    const newEvent = { title: eventTitle.value, start: eventStart.value, end: eventEnd.value };
    await axios.post('/api/events', newEvent);
    await fetchEvents();
    eventTitle.value = '';
    eventStart.value = '';
    eventEnd.value = '';
    formVisible.value = false; // Hide the form after adding event
  } catch (error) {
    console.error('Error adding event:', error);
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

// Fetch events on component mount
onMounted(fetchEvents);
</script>

<style scoped>
form {
  background-color: rgba(0, 163, 224, 0.2); /* Azzurro del welcome con trasparenza */
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

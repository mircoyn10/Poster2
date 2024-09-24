<template>
  <AppLayout>
    <Head title="Event Calendar" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Event Calendar</h2>
    </template>

    <main>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Your Events</h3>
              <p class="mt-1 text-sm text-gray-500">
                View and manage your scheduled events.
              </p>
            </div>

            <!-- Calendario compatto -->
            <div class="border-t border-gray-200 p-4">
              <FullCalendar :options="calendarOptions" eventContent="{ event } => event.title" />
            </div>

            <!-- Form fisso per aggiungere eventi -->
            <div class="p-4">
              <h4 class="text-lg font-medium text-gray-900">Aggiungi un nuovo evento</h4>
              <form @submit.prevent="addEvent" class="mt-4">
                <div class="mb-4">
                  <label for="title" class="block text-sm font-medium text-gray-700">Titolo</label>
                  <input
                    type="text"
                    v-model="eventTitle"
                    id="title"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-300"
                    placeholder="Inserisci il titolo dell'evento"
                    required
                  />
                </div>

                <!-- Data Picker -->
                <div class="mb-4">
                  <label for="start" class="block text-sm font-medium text-gray-700">Data Inizio</label>
                  <input
                    type="datetime-local"
                    v-model="eventStart"
                    id="start"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-300"
                    required
                  />
                </div>
                <div class="mb-4">
                  <label for="end" class="block text-sm font-medium text-gray-700">Data Fine</label>
                  <input
                    type="datetime-local"
                    v-model="eventEnd"
                    id="end"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-300"
                    required
                  />
                </div>

                <button
                  type="submit"
                  class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition"
                >
                  Aggiungi Evento
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction'; // Plugin per il click

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Stato del form per aggiungere eventi
const eventTitle = ref('');
const eventStart = ref('');
const eventEnd = ref('');
const selectedDate = ref(null);

// Funzione per gestire il clic sulla data e aggiornare i picker
const handleDateClick = (info) => {
  const formattedDate = new Date(info.dateStr).toISOString().slice(0, 16); // Formatta per il campo datetime-local
  eventStart.value = formattedDate;
  eventEnd.value = formattedDate;
  selectedDate.value = formattedDate;
};


// Configurazione del calendario, inclusi il mouse over e il click
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true, // Abilita la selezione
  dateClick: handleDateClick, // Aggiunta della funzione per gestire il clic sulla data
  events: [],  // Gli eventi verranno caricati da fetchEvents
  eventMouseEnter(info) { // Funzione per illuminare al mouse over
    info.el.style.backgroundColor = '#d1fae5'; // Cambia colore al passaggio del mouse
  },
  eventMouseLeave(info) {
    info.el.style.backgroundColor = ''; // Ripristina il colore quando il mouse esce
  }
});
watch(calendarOptions.value.events, (newEvents) => {
  console.log('Eventi aggiornati:', newEvents); // Verifica se gli eventi vengono aggiornati correttamente
});

// Funzione per caricare gli eventi dal server e montarli nel calendario
const fetchEvents = async () => {
  try {
    const { data } = await axios.get('/api/events');
    console.log('Dati degli eventi dall\'API:', data); // Log per verificare le date

    calendarOptions.value.events = data.map(event => {
      const startDate = new Date(event.start); // Creazione dell'oggetto Date
      const endDate = new Date(event.end); // Creazione dell'oggetto Date

      // Controllo della validità delle date
      if (isNaN(startDate) || isNaN(endDate)) {
        console.error('Data non valida:', event.start, event.end);
        return null; // Salta questo evento se le date sono invalide
      }

      // Se start e end sono uguali, imposta end un millisecondo dopo
      if (startDate.getTime() === endDate.getTime()) {
        endDate.setTime(endDate.getTime() + 1); // Aggiungi un millisecondo
      }

      return {
        id: event.id,
        title: event.title,
        start: startDate.toISOString().replace(/\.\d{3}Z$/, 'Z'),  // Rimuovi millisecondi
        end: endDate.toISOString().replace(/\.\d{3}Z$/, 'Z'),      // Rimuovi millisecondi
      };
    }).filter(event => event !== null); // Rimuovi eventi nulli

  } catch (error) {
    console.error('Errore nel caricamento degli eventi:', error);
  }
};










// Funzione per aggiungere un nuovo evento
const addEvent = async () => {
  try {
    const newEvent = {
      title: eventTitle.value,
      start_date: eventStart.value,
      end_date: eventEnd.value
    };

    // Effettua la richiesta POST per aggiungere un evento
    await axios.post('/api/events', newEvent);

    // Ricarica gli eventi dopo aver aggiunto il nuovo evento
    await fetchEvents();

    // Resetta il form dopo l'aggiunta dell'evento
    eventTitle.value = '';
    eventStart.value = '';
    eventEnd.value = '';
    selectedDate.value = null;
  } catch (error) {
    console.error('Errore nell\'aggiunta dell\'evento:', error);
  }
};

// Carica gli eventi quando il componente è montato
onMounted(fetchEvents);
</script>

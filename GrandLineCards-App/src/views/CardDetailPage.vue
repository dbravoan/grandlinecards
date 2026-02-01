<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-buttons slot="start">
          <ion-back-button default-href="/home" color="warning"></ion-back-button>
        </ion-buttons>
        <ion-title class="font-display text-amber-500">{{ card?.id || 'Detalle' }}</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true" class="ion-padding bg-slate-900">
      
      <div v-if="loading" class="flex h-full items-center justify-center">
        <ion-spinner name="crescent" color="primary"></ion-spinner>
      </div>

      <div v-else-if="card" class="flex flex-col gap-6 pb-20">
        <!-- Card Image -->
        <div class="shadow-2xl rounded-xl overflow-hidden border-2 border-amber-500/30">
            <img :src="card.image_url || 'https://via.placeholder.com/400x560'" :alt="card.name" class="w-full h-auto object-cover">
        </div>

        <!-- Info Card -->
        <div class="bg-slate-800 rounded-lg p-5 border border-slate-700 space-y-4">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ card.name }}</h1>
                <p class="text-amber-500 font-mono text-sm">{{ card.id }} • {{ card.rarity }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-700 pt-4">
                <div class="bg-slate-900 p-3 rounded text-center">
                    <p class="text-gray-400 text-xs uppercase">Poder</p>
                    <p class="text-xl font-bold text-red-400">{{ card.power }}</p>
                </div>
                <div class="bg-slate-900 p-3 rounded text-center">
                    <p class="text-gray-400 text-xs uppercase">Coste</p>
                    <p class="text-xl font-bold text-blue-400">{{ card.cost }}</p>
                </div>
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2">
                <ion-chip v-for="attr in card.attributes" :key="attr" color="warning" outline="true">
                    {{ attr }}
                </ion-chip>
            </div>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { 
    IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButtons, IonBackButton,
    IonSpinner, IonChip
} from '@ionic/vue';
import { useRoute } from 'vue-router';
import { ref, onMounted } from 'vue';
import { cardService } from '@/services/api';

const route = useRoute();
const card = ref(null);
const loading = ref(true);

onMounted(async () => {
    const cardId = route.params.id;
    try {
        card.value = await cardService.getCardDetail(cardId);
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
});
</script>

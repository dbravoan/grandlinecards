<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-title class="font-display font-bold text-amber-500">Grand Line Cards</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true" class="ion-padding">
      <ion-header collapse="condense">
        <ion-toolbar color="secondary">
          <ion-title size="large" class="text-amber-500">Explorar</ion-title>
        </ion-toolbar>
      </ion-header>

      <!-- Search -->
      <ion-searchbar placeholder="Buscar piratas..." class="mb-4"></ion-searchbar>

      <!-- Featured Section -->
      <h2 class="text-xl font-bold text-white mb-2 pl-1">Se Busca</h2>
      <div v-if="loading" class="flex justify-center p-8">
        <ion-spinner name="crescent" color="primary"></ion-spinner>
      </div>

      <div v-else class="grid grid-cols-2 gap-4">
        <div v-for="card in cards" :key="card.id" class="flex flex-col" @click="openDetail(card.id)">
            <ion-card class="m-0 bg-slate-800 border-none shadow-lg active:scale-95 transition-transform duration-200">
                <img :src="card.image_url || 'https://via.placeholder.com/300x420'" :alt="card.name" class="w-full h-auto object-cover rounded-t-lg" />
                <ion-card-header>
                    <ion-card-subtitle class="text-amber-500 font-bold uppercase">{{ card.id }}</ion-card-subtitle>
                    <ion-card-title class="text-white text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ card.name }}</ion-card-title>
                </ion-card-header>
            </ion-card>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { 
    IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonSearchbar, 
    IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle, IonSpinner,
    IonButton, IonIcon, toastController
} from '@ionic/vue';
import { camera } from 'ionicons/icons';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { cardService } from '@/services/api';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';
import { scanner } from '@/utils/scanner';

const router = useRouter();
const cards = ref([]);
const loading = ref(true);
const scanning = ref(false);

const openDetail = (id) => {
    router.push(`/card/${id}`);
};

const handleSearch = async (event) => {
    const query = event.target.value;
    loading.value = true;
    try {
        const response = await cardService.getCards({ q: query });
        cards.value = response; 
    } catch (error) {
        console.error('Search failed', error);
    } finally {
        loading.value = false;
    }
};

const scanCard = async () => {
    try {
        const image = await Camera.getPhoto({
            quality: 90,
            allowEditing: false,
            resultType: CameraResultType.Base64,
            source: CameraSource.Camera,
            promptLabelHeader: 'Scan Card',
            promptLabelPhoto: 'From Photos',
            promptLabelPicture: 'Take Picture'
        });

        if (image.base64String) {
            scanning.value = true;
            const base64Data = `data:image/${image.format};base64,${image.base64String}`;
            
            const code = await scanner.recognizeCardCode(base64Data);
            
            if (code) {
                const toast = await toastController.create({
                    message: `Código detectado: ${code}`,
                    duration: 1500,
                    color: 'success',
                    position: 'top'
                });
                await toast.present();
                router.push(`/card/${code}`);
            } else {
                const toast = await toastController.create({
                    message: 'No se pudo detectar código. Asegúrate de capturar la esquina inferior derecha.',
                    duration: 3000,
                    color: 'warning'
                });
                await toast.present();
            }
        }
    } catch (error) {
        console.error('Camera/OCR error:', error);
        if (error.message !== 'User cancelled photos app') {
             const toast = await toastController.create({
                message: 'Error al abrir la cámara o procesar imagen.',
                duration: 2000,
                color: 'danger'
            });
            await toast.present();
        }
    } finally {
        scanning.value = false;
    }
};

onMounted(async () => {
    try {
        const response = await cardService.getCards();
        cards.value = response; 
    } catch (error) {
        console.error('Failed to load cards', error);
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
ion-searchbar {
    --background: #1e293b; /* Grand-700 */
    --color: #fff;
    --placeholder-color: #94a3b8;
    --icon-color: #cbd5e1;
    --border-radius: 9999px;
}
</style>

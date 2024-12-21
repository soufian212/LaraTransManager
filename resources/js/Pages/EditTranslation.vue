<template>
    <div class="w-full text-right mb-4">
      <SplitButton icon="pi pi-save" label="Save" @click="save" :model="items" :disabled="form.processing" :loading="form.processing" />
    </div>
    <div class="grid grid-cols-2 h-full gap-4">
      <div class="bg-white shadow-lg rounded-lg border-2">
        <div class="flex items-center gap-4 border-b-2 p-4 text-gray-600">
          <i :class="`flag flag-country-${source_lang[0]} flag-xm rounded`"></i>
          <span>{{ source_lang[1] }}</span>


        </div>
        <div class="p-4 h-32">
          <p>
            <template v-for="word in source.split(' ')" :key="word">
              <span :class="{ 'bg-indigo-100 text-indigo-500 rounded p-[2px]': word.startsWith(':'), }" >{{ word + " " }} </span>
              <span> </span>
            </template>
          </p>
        </div>
  
        <div class="border-t-2 p-4 flex items-center gap-2 text-gray-600">
          <i class="pi pi-file" />
          <span>{{ translation.file }}.php</span>
        </div>
      </div>
  
      <div class="bg-white shadow-lg rounded-lg border-2">
        <div class="flex items-center gap-4 border-b-2 p-4 text-gray-600 justify-between">
          <div class="flex items-center gap-4">
            
            <i :class="`flag flag-country-${target_lang[0]} flag-xm rounded`"></i>
          <span>{{ target_lang[1] }}</span>
          </div>

          <p v-if="form.errors.translatedSource" class="text-red-500">{{form.errors.translatedSource}}</p>

        </div>
        <div class="p-4 h-32">
          <textarea class="w-full h-full outline-none resize-none" autofocus v-model="form.translatedSource" />
        </div>
  
        <div class="border-t-2 p-4 flex items-center gap-2 text-gray-600">
          <i class="pi pi-file" />
          <span>{{ translation.file }}.php</span>
        </div>
      </div>
      
    </div>
  </template>
  
  <script setup>
  import { useForm } from "@inertiajs/vue3";
  import SplitButton from "primevue/splitbutton";
import { ref } from "vue";
  import { route } from "ziggy-js";
  
  const props = defineProps({
    translation: Object,
    lang: String,
    source: String,
    source_lang: Object,
    target_lang: Object,
  });

  const translateNext = ref(false);
  
  const form = useForm({
      translatedSource: props.translation.value,
      translation: props.translation,
      translateNext: translateNext.value,
  });

  const items = [
    {
      label: "Save & Next",
      command: () => {
        translateNext.value = true;
        form.translateNext = translateNext.value;
        save();
        }
    },
  ];
  
  function extractPlaceholders(text) {
      // Ensure text is a string
      if (typeof text !== 'string') {
          return [];
      }
      // Match all placeholders starting with ":"
      return text.match(/:\w+/g) || [];
  }
  
  function validatePlaceholders() {
      const sourcePlaceholders = extractPlaceholders(props.source);
      const translationPlaceholders = extractPlaceholders(form.translatedSource);
  
      // Check if all placeholders in the source exist in the translation
      for (const placeholder of sourcePlaceholders) {
          if (!translationPlaceholders.includes(placeholder)) {
              alert(`The placeholder "${placeholder}" is missing in the translation.`);
              return false;
          }
      }
      return true;
  }
  
  function save() {
      // Perform validation before submitting the form
      if (!validatePlaceholders()) {
          return;
      }
  
      // Submit the form if validation passes
      form.put(route("ltm.translations.update", props.translation.id));
  }
  
  </script>
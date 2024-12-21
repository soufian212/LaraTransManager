<template>
  <div class="w-full text-right mb-4">
    <SplitButton
      icon="pi pi-save"
      label="Save"
      @click="save"
      :model="items"
      :disabled="form.processing"
      :loading="form.processing"
    />
  </div>
  <div class="grid grid-cols-2 h-full gap-4">
    <div class="bg-white shadow-lg rounded-lg border-2">
      <div class="flex items-center gap-4 border-b-2 p-4 text-gray-600">
        <i :class="`flag flag-country-${lang[0]} flag-xm rounded`"></i>
        <span>{{ lang[1] }}</span>
      </div>
      <div class="p-4 h-32">
        <textarea
          class="w-full h-full outline-none resize-none"
          autofocus
          v-model="form.phrase"
          placeholder="Enter your phrase"
        />
        <Message v-if="form.errors.phrase" severity="error" >{{form.errors.phrase}}</Message>
      </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg border-2">
      <div class="flex items-center gap-4 border-b-2 p-4 text-gray-600 justify-between">
        <div class="flex items-center gap-4">
          <i class="pi pi-cog" />
          <span class="font-medium">Options</span>
        </div>
      </div>
      <div class="p-4 h-32 space-y-4">
        <div>
          <Select
            v-model="form.file"
            :options="props.files"
            optionLabel="file"
            placeholder="Select a File"
            class="w-full md:w-56"
            fluid
          />
          <Message v-if="form.errors.file" severity="error" >{{form.errors.file}} </Message>
        </div>
        <div>
          <InputText v-model="form.key" placeholder="Add a key" fluid />
            <Message v-if="form.errors.key" severity="error" >{{form.errors.key}} </Message>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import SplitButton from "primevue/splitbutton";
import { ref } from "vue";
import { route } from "ziggy-js";
import Select from "primevue/select";

import InputText from "primevue/inputtext";
import { Message } from "primevue";

const props = defineProps({
  files: Object,
  lang: Object,
});



const form = useForm({
  phrase: "",
  file: "",
  key: "",
});

const items = [
  {
    label: "Cancel",
    command: () => {
      router.visit(route("ltm.translations.sourceShow"));
    },
  },
];

function save() {
  form.post(route("ltm.translations.sourceStore"));
}
</script>

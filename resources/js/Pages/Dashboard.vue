<template>
  <div>
    <div class="flex justify-end mb-4 gap-2">
      <Button label="Clear Cache" icon="pi pi-trash" severity="secondary" @click="router.get(route('ltm.translations.clearAllCache'))" />
      <Button label="New" icon="pi pi-plus" @click="showCreateModal = true" />
    </div>
  </div>

  <ConfirmDialog></ConfirmDialog>
  <div
    class="bg-white border-2 rounded-lg h-full shadow-lg p-4 grid lg:grid-cols-4 gap-4 md:grid-cols-2 sm:grid-cols-1"
  >
    <div
      class="mt-4 border rounded p-4 shadow-lg duration-300 h-60 relative overflow-hidden"
      v-for="(translation, index) in translations.data"
      :key="index"
    >
      <div class="flex items-center gap-4 mb-4">
        <i :class="`flag flag-country-${translation.details[0]} flag-xm rounded`"></i>
        <h4 class="font-semibold">{{ translation.details[1] }}</h4>
        <Tag :value="translation.lang" severity="secondary"></Tag>
      </div>
      <div class="w-full">
        <template v-if="translation.lang == 'en'">
          Source keys: {{ translation.total_keys }}
        </template>
        <div>
          <MeterGroup
            :value="[
              {
                label: 'Translated',
                value: translation.total_keys,
                color: 'var(--p-primary-color)',
              },
            ]"
            labelPosition="start"
            :max="englishTotalKeys"
          >
            <template #label="{ value }" style="display: none">
              <div class="flex flex-wrap gap-4" style="display: "></div>
            </template>
            <template #meter="slotProps">
              <span
                :class="slotProps.class + ' bg-indigo-800'"
                :style="{ width: slotProps.size }"
              />
            </template>
            <template #start="{ totalPercent }">
              <div class="mb-2 relative">
                <span :style="{ width: totalPercent + '%' }" class="absolute text-right"
                  >{{ totalPercent }}%</span
                >
              </div>
            </template>
          </MeterGroup>
        </div>
      </div>
      <div
        :class="
          'absolute bottom-0 left-0 w-full bg-gray-100 border-t-2 grid grid-cols-2' +
          (translation.lang === 'en' ? 'border-r-0' : '')
        "
      >
        <Link
          v-if="translation.lang === 'en'"
          :href="route('ltm.translations.sourceShow')"
          class="p-4 text-center hover:bg-gray-300/75 border-r"
          ><i class="pi pi-cog"
        /></Link>
        <Link
          v-else
          :href="route('ltm.translations.show', translation.lang)"
          class="p-4 text-center hover:bg-gray-300/75 border-r"
          ><i class="pi pi-pencil"
        /></Link>

        <button
          v-if="translation.lang !== 'en'"
          class="p-4 text-center hover:bg-gray-300/75 border-l"
          @click="confirmDeletion(translation.lang)"
        >
          <i class="pi pi-trash" />
        </button>
      </div>
    </div>
  </div>
  <div class="flex justify-between mt-4">
    <div class="flex gap-2 justify-center items-center w-full">
      <template v-for="page in translations.links">
        <Link
          :disabled="!page.url"
          :class="[
            'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
            page.url
              ? 'hover:bg-gray-100 text-gray-700'
              : 'text-gray-400 cursor-not-allowed',
            page.active
              ? 'bg-indigo-600 text-white hover:bg-indigo-700'
              : 'bg-white border border-gray-300',
          ]"
          @click="router.get(page.url)"
          v-html="page.label"
        >
        </Link>
      </template>
    </div>
  </div>

  <Dialog
    v-model:visible="showCreateModal"
    modal
    header="Add New Language"
    :style="{ width: '50vw' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <div class="flex items-center gap-4 mb-4">
      <label for="language" class="font-semibold w-24">Language</label>
      <Select
        fluid
        v-model="createLanguageForm.selectedLanguage"
        editable
        :options="language"
        optionLabel="name"
        placeholder="Select Language"
        class="w-full md:w-56"
      />
    </div>
    <Message severity="error" v-show="createLanguageForm.errors.selectedLanguage">{{
      createLanguageForm.errors.selectedLanguage
    }}</Message>
    <div class="flex justify-end gap-2">
      <Button
        type="button"
        label="Cancel"
        severity="secondary"
        @click="showCreateModal = false"
      ></Button>
      <Button
        type="button"
        label="Save"
        @click="createNewLanguage"
        :disabled="!createLanguageForm.selectedLanguage || createLanguageForm.processing"
        :loading="createLanguageForm.processing"
      ></Button>
    </div>
  </Dialog>
</template>

<script setup>
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Tag from "primevue/tag";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Message from "primevue/message";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import MeterGroup from "primevue/metergroup";
import ConfirmDialog from "primevue/confirmdialog";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps({
  translations: Object,
  langs: Object,
  englishTotalKeys: Number,
});

const showCreateModal = ref(false);
const createLanguageForm = useForm({
  selectedLanguage: null,
});
const language = ref(
  Object.entries(props.langs).map(([key, value]) => ({
    code: key,
    flag: value[0],
    name: value[1],
    country: value[2],
  }))
);

function createNewLanguage() {
  createLanguageForm.post(
    route("ltm.translations.store", createLanguageForm.selectedLanguage["code"]),
    {
      onSuccess: () => {
        showCreateModal.value = false;
      },
    }
  );
}

function getPage(url) {
  if (url) {
    router.visit(url);
  }
}

function onPageChange(event) {
  currentPage.value = event.page + 1;
  getPage(`/translations?page=${currentPage.value}`);
}

const confirm = useConfirm();
const toast = useToast();

const confirmDeletion = (lang) => {
  confirm.require({
    message: "Are you sure you want to Delete this Language?",
    header: "Confirmation",
    icon: "pi pi-exclamation-triangle",
    rejectProps: {
      label: "Cancel",
      severity: "secondary",
      outlined: true,
    },
    acceptProps: {
      label: "Delete",
      severity: "danger",
    },
    accept: () => {
      router.delete(route("ltm.translations.destroy", lang));
    },
  });
};
</script>

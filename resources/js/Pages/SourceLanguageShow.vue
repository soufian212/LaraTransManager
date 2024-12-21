<template>
    <div class="w-full text-right mb-4">
    <Link :href="route('ltm.translations.sourceCreate')" ><Button icon="pi pi-plus" /></Link>
    </div>
    <div class="bg-white shadow-lg rounded-lg border-2">
        <table class="w-full mt-4 text-gray-700">
            <thead class="border">
                <tr >
                    <th class="text-left p-2 border-l p-2 w-1/6">File</th>
                    <th class="text-left p-2 border-l p-2  ">Keys</th>
                    <th class="text-left p-2 border-l p-2 w-4/5">Translation</th>
                </tr>
            </thead>
            <tbody>
                <tr   v-for="translation in translations.data" class="border-b  p-2" >
                   
                    <td class="p-4 border-r">{{ translation.file }}</td>
                    <td class="p-4 border-r">{{ translation.key }}</td>
                    <td class="p-4 border-r">{{ translation.value }}</td>
                    <td class="hover:bg-indigo-100"><Link :href="route('ltm.translations.sourceEdit', translation)" class="p-4 hover:bg-indigo-100"><i class="pi pi-pencil" /></Link></td>
                    <td class="hover:bg-red-100 hover:text-red-500"><Link :href="route('ltm.translations.sourceDelete', translation)" method="delete" class="p-4 hover:bg-red-100"><i class="pi pi-trash" /></Link></td>
                    
                </tr>
            </tbody>
        </table>
        <div class="flex justify-between mt-4">
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
          :href="page.url"
          v-html="page.label"
        >
        </Link>
      </template>
    </div>
  </div>
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { Link } from "@inertiajs/vue3";
import Paginator from 'primevue/paginator';
import { Button } from "primevue";

const props = defineProps({
    translations: Object,
    lang: String,
});



function getPage(url) {
    if (url) {
        router.visit(url);
    }
}

function onPageChange(event) {
    currentPage.value = event.page + 1;
    getPage(`/translations?page=${currentPage.value}`);
}

</script>

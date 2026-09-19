<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Plus, X } from 'lucide-vue-next';
import {
    Dialog, DialogClose, DialogContent, DialogDescription,
    DialogFooter, DialogHeader, DialogTitle, DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { update } from '@/routes/JobApplication';

const open = ref(false);

const jobLink = ref('');
const jobLinkError = ref('');

const props = defineProps({
   application: {
        type: Object as () => {
            id: number,
            job_description?: string | null,
            job_url?: string[] | null,
        },
        required: true,
    },
})

const form = useForm({
      job_description: props.application.job_description ?? '',
      job_url: props.application.job_url ?? [],
});

const addJobLink = () => {
    const url = jobLink.value.trim();

    jobLinkError.value = '';

    if (!url) {
        jobLinkError.value = 'Please enter a URL.';
        return;
    }

    try {
        new URL(url);
    } catch {
        jobLinkError.value = 'Please enter a valid URL, including https://.';
        return;
    }

    form.job_url.push(url);
    jobLink.value = '';
};

function handleOpenChange(value: boolean) {
    open.value = value;
    if (!value) form.reset();
}

function submit() {
    form.submit(update(props.application.id), {
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <Dialog :open="open" @update:open="handleOpenChange">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <form class="space-y-6" @submit.prevent="submit">
                <DialogHeader>
                    <DialogTitle>View Application Details</DialogTitle>
                    <DialogDescription>
                        Job details for this application.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-5">
                    <div class="space-y-2">
                        <div class="flex items-center gap-1">
                            <h3 class="text-sm font-medium">Full Job Description</h3>
                            <p class="text-xs text-muted-foreground">
                                (Qualifications, Key Responsibilities, Salary, Benefits, etc.)
                            </p>
                        </div>

                        <Textarea
                            v-model="form.job_description"
                            class="min-h-32"
                            placeholder="Add the job description..."
                        />
                        <InputError :message="form.errors.job_description" />
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-1">
                            <h3 class="text-sm font-medium">Job URL</h3>
                            <p class="text-xs text-muted-foreground">
                                (External link to the job posting, Company website, etc.)
                            </p>
                        </div>

                        <template v-for="(link, index) in form.job_url">
                            <div class="flex items-center gap-1">
                                <Input name="links[]" v-model="form.job_url[index]" />
                                <button type="button" @click="form.job_url.splice(index, 1)">
                                    <X />
                                </button>

                                
                                <InputError :message="form.errors[`job_url.${index}`]" />
                            </div>

                        </template>

                        <div class="flex items-center gap-1">

                            <Input 
                                v-model="jobLink" 
                                placeholder="https://example.com" 
                                class="flex-1" 
                                autocomplete="url"
                                spellcheck="false"
                                />
                            <button 
                                type="button" 
                                @click="addJobLink" 
                                :disabled="jobLink.trim().length === 0">
                                <Plus />
                            </button>

                        </div>

                        <InputError :message="jobLinkError" />
                       
                    </div>
                </div>

                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="form.processing">Save details</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

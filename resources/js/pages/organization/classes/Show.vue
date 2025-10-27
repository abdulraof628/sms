<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import { 
    GraduationCap, 
    Building2, 
    Users, 
    Edit, 
    ArrowLeft,
    Calendar,
    User,
    BookOpen,
    Layers,
    Users2,
    Volume2,
    LayoutGrid
} from 'lucide-vue-next';

interface Staff {
    id: string;
    name: string;
}

interface ClassData {
    id: string;
    name: string;
    code: string;
    curriculum_type: string;
    grade_level: string;
    stream: string | null;
    academic_session: string;
    language_medium: string;
    capacity: number;
    advisor_id: string | null;
    description: string;
    notes: string;
    status: boolean;
    created_at: string;
    updated_at: string;
    branch: {
        id: string;
        name: string;
    };
    advisor: Staff | null;
    students: Array<{
        id: string;
        first_name: string;
        last_name: string;
        roll_number: string;
        status: boolean;
    }>;
}

const props = defineProps<{
    class: ClassData;
}>();

const classInfo = computed(() => props.class);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Classes',
        href: '/classes',
    },
    {
        title: props.class.name,
        href: `/classes/${props.class.id}`,
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getCurriculumLabel = (type: string) => {
  const curriculumMap: Record<string, string> = {
    'kurikulum_merdeka': 'Kurikulum Merdeka',
    'kss': 'KSS',
    'kssm': 'KSSM (Secondary)',
    'kbsr': 'KBSR (Primary)',
    'kbsm': 'KBSM (Secondary)',
    'spm': 'SPM (Exam)',
    'stpm': 'STPM (Advanced)',
    'modular': 'Modular System',
  }
  return curriculumMap[type] || type
}

const getGradeLevelLabel = (level: string) => {
  const gradeLevelMap: Record<string, string> = {
    'year_1': 'Year 1 (Tahun 1)',
    'year_2': 'Year 2 (Tahun 2)',
    'year_3': 'Year 3 (Tahun 3)',
    'year_4': 'Year 4 (Tahun 4)',
    'year_5': 'Year 5 (Tahun 5)',
    'year_6': 'Year 6 (Tahun 6)',
    'form_1': 'Form 1 (Tingkatan 1)',
    'form_2': 'Form 2 (Tingkatan 2)',
    'form_3': 'Form 3 (Tingkatan 3)',
    'form_4': 'Form 4 (Tingkatan 4)',
    'form_5': 'Form 5 (Tingkatan 5)',
    'form_6': 'Form 6 (Tingkatan 6)',
  }
  return gradeLevelMap[level] || level
}

const getStreamLabel = (stream: string | null) => {
  if (!stream) return 'General'
  const streamMap: Record<string, string> = {
    'general': 'General (Umum)',
    'science': 'Science (Sains)',
    'arts': 'Arts (Sastera)',
    'commerce': 'Commerce (Perdagangan)',
    'technical': 'Technical (Teknik)',
    'vocational': 'Vocational (Vokasional)',
    'engineering': 'Engineering (Kejuruteraan)',
    'biology': 'Biology (Biologi)',
    'accounting_finance': 'Accounting & Finance',
    'humanities_social': 'Humanities & Social Studies',
  }
  return streamMap[stream] || stream
}

const getLanguageMediumLabel = (medium: string) => {
  const languageMap: Record<string, string> = {
    'malay': 'Bahasa Melayu (Malay)',
    'english': 'English',
    'chinese': 'Mandarin (中文)',
    'tamil': 'Tamil (தமிழ்)',
    'bilingual': 'Bilingual',
    'english_malay': 'English - Malay',
  }
  return languageMap[medium] || medium
}
</script>

<template>
    <Head :title="classInfo.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">{{ classInfo.name }}</h1>
                    <p class="text-muted-foreground mt-2">Class details and information</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('classes.index')">
                        <Button variant="outline">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Classes
                        </Button>
                    </Link>
                    <Link :href="route('classes.edit', classInfo.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Class
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Malaysian Education System Information -->
            <div class="grid gap-6 lg:grid-cols-4">
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <BookOpen class="h-6 w-6 text-blue-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Curriculum</p>
                                <p class="text-lg font-semibold">{{ getCurriculumLabel(classInfo.curriculum_type) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <Layers class="h-6 w-6 text-green-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Grade Level</p>
                                <p class="text-lg font-semibold">{{ getGradeLevelLabel(classInfo.grade_level) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <LayoutGrid class="h-6 w-6 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Stream</p>
                                <p class="text-lg font-semibold">{{ getStreamLabel(classInfo.stream) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <Calendar class="h-6 w-6 text-orange-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Session</p>
                                <p class="text-lg font-semibold">{{ classInfo.academic_session }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Class Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <GraduationCap class="h-5 w-5" />
                                <span>Class Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Class Name</label>
                                    <p class="text-sm">{{ classInfo.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Class Code</label>
                                    <p class="text-sm font-mono">{{ classInfo.code }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Branch</label>
                                    <Link :href="route('branches.show', classInfo.branch.id)" class="text-sm text-primary hover:underline">
                                        {{ classInfo.branch.name }}
                                    </Link>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Status</label>
                                    <Badge :variant="classInfo.status ? 'default' : 'secondary'">
                                        {{ classInfo.status ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </div>
                            </div>
                            
                            <div v-if="classInfo.description">
                                <label class="text-sm font-medium text-muted-foreground">Description</label>
                                <p class="text-sm mt-1">{{ classInfo.description }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Malaysian Education Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <BookOpen class="h-5 w-5" />
                                <span>Malaysian Education Details</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Curriculum Type</label>
                                    <Badge variant="outline" class="mt-1">{{ getCurriculumLabel(classInfo.curriculum_type) }}</Badge>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Grade Level / Tingkatan</label>
                                    <Badge variant="outline" class="mt-1">{{ getGradeLevelLabel(classInfo.grade_level) }}</Badge>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Stream</label>
                                    <Badge variant="outline" class="mt-1">{{ getStreamLabel(classInfo.stream) }}</Badge>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Academic Session</label>
                                    <p class="text-sm font-semibold mt-1">{{ classInfo.academic_session }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Language Medium</label>
                                    <Badge variant="secondary" class="mt-1">{{ getLanguageMediumLabel(classInfo.language_medium) }}</Badge>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Class Capacity</label>
                                    <p class="text-sm font-semibold mt-1">{{ classInfo.capacity }} Students</p>
                                </div>
                            </div>

                            <div v-if="classInfo.advisor">
                                <label class="text-sm font-medium text-muted-foreground">Class Advisor</label>
                                <p class="text-sm mt-1">{{ classInfo.advisor.name }}</p>
                            </div>

                            <div v-if="classInfo.notes">
                                <label class="text-sm font-medium text-muted-foreground">Additional Notes</label>
                                <p class="text-sm mt-1">{{ classInfo.notes }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Statistics -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Class Statistics</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Users class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium">Total Students</span>
                                </div>
                                <Badge variant="secondary">{{ classInfo.students.length }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <User class="h-4 w-4 text-green-500" />
                                    <span class="text-sm font-medium">Active Students</span>
                                </div>
                                <Badge variant="secondary">{{ classInfo.students.filter(s => s.status).length }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Users2 class="h-4 w-4 text-blue-500" />
                                    <span class="text-sm font-medium">Class Capacity</span>
                                </div>
                                <Badge variant="secondary">{{ classInfo.capacity }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Volume2 class="h-4 w-4 text-purple-500" />
                                    <span class="text-sm font-medium">Occupancy</span>
                                </div>
                                <Badge :variant="classInfo.students.length > classInfo.capacity ? 'destructive' : 'default'">
                                    {{ Math.round((classInfo.students.length / classInfo.capacity) * 100) }}%
                                </Badge>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Activity -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Recent Activity</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Created</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(classInfo.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(classInfo.updated_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Students -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center justify-between">
                        <span class="flex items-center space-x-2">
                            <Users class="h-5 w-5" />
                            <span>Students ({{ classInfo.students.length }}/{{ classInfo.capacity }})</span>
                        </span>
                        <Link :href="route('students.create')">
                            <Button size="sm">Add Student</Button>
                        </Link>
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="classInfo.students.length > 0" class="space-y-3">
                        <div v-for="student in classInfo.students" :key="student.id" 
                             class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                    <User class="h-4 w-4 text-primary" />
                                </div>
                                <div>
                                    <p class="font-medium">{{ student.first_name }} {{ student.last_name }}</p>
                                    <p class="text-sm text-muted-foreground">Roll: {{ student.roll_number }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Badge :variant="student.status ? 'default' : 'secondary'">
                                    {{ student.status ? 'Active' : 'Inactive' }}
                                </Badge>
                                <Link :href="route('students.show', student.id)">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center text-muted-foreground py-8">
                        <Users class="h-12 w-12 mx-auto mb-4 text-muted-foreground/50" />
                        <p>No students enrolled in this class yet.</p>
                        <Link :href="route('students.create')">
                            <Button class="mt-4">Add First Student</Button>
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

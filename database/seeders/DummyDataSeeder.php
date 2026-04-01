<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all()->keyBy('name');
        $password = Hash::make('password');

        // --- Teachers ---
        $teachers = collect([
            ['name' => 'ئەحمەد کەریم',    'username' => 'ahmad.karim'],
            ['name' => 'سارا ئەمین',      'username' => 'sara.amin'],
            ['name' => 'ئاریان حەسەن',    'username' => 'aryan.hasan'],
            ['name' => 'نازدار عەلی',     'username' => 'nazdar.ali'],
            ['name' => 'کاروان مستەفا',   'username' => 'karwan.mustafa'],
        ])->map(fn ($data) => User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'password' => $password,
            'role'     => UserRole::Teacher,
        ]));

        // --- Students ---
        $studentsData = [
            // Math students
            ['name' => 'دیلنیا ئومەر',    'username' => 'dilnia.omar',    'dept' => 'بیرکاری',    'stage' => 1],
            ['name' => 'هاوژین جەلال',    'username' => 'hawzhin.jalal',  'dept' => 'بیرکاری',    'stage' => 2],
            ['name' => 'سەروەر ئیبراهیم', 'username' => 'sarwar.ibrahim', 'dept' => 'بیرکاری',    'stage' => 3],
            ['name' => 'شیرین باکر',      'username' => 'shirin.baker',   'dept' => 'بیرکاری',    'stage' => 1],
            ['name' => 'ڕاوەژ تاهیر',     'username' => 'rawezh.tahir',   'dept' => 'بیرکاری',    'stage' => 4],
            // Computer students
            ['name' => 'ئارام سەعید',     'username' => 'aram.saeed',     'dept' => 'کۆمپیوتەر', 'stage' => 1],
            ['name' => 'ژینا مەحمود',     'username' => 'zhina.mahmud',   'dept' => 'کۆمپیوتەر', 'stage' => 2],
            ['name' => 'لاوەک رەشید',     'username' => 'lawek.rashid',   'dept' => 'کۆمپیوتەر', 'stage' => 3],
            ['name' => 'هەرێم عەبدوڵڵا',  'username' => 'harim.abdullah', 'dept' => 'کۆمپیوتەر', 'stage' => 2],
            ['name' => 'سۆزان نەجم',      'username' => 'sozan.najm',     'dept' => 'کۆمپیوتەر', 'stage' => 5],
            // Physics students
            ['name' => 'کۆمەڵ یوسف',      'username' => 'komal.yusuf',    'dept' => 'فیزیک',      'stage' => 1],
            ['name' => 'بینا حامد',       'username' => 'bina.hamid',     'dept' => 'فیزیک',      'stage' => 3],
            ['name' => 'ئاوات نوری',      'username' => 'awat.nuri',      'dept' => 'فیزیک',      'stage' => 2],
            ['name' => 'ریباز خالید',     'username' => 'ribaz.khalid',   'dept' => 'فیزیک',      'stage' => 4],
            ['name' => 'گولالە حسین',     'username' => 'gulala.husain',  'dept' => 'فیزیک',      'stage' => 1],
        ];

        $students = collect($studentsData)->map(fn ($data) => User::create([
            'name'          => $data['name'],
            'username'      => $data['username'],
            'password'      => $password,
            'role'          => UserRole::Student,
            'department_id' => $departments[$data['dept']]->id,
            'stage'         => $data['stage'],
        ]));

        // --- Classrooms ---
        $classroomNames = [
            'بیرکاری — قۆناغی یەکەم',
            'کۆمپیوتەر — قۆناغی دووەم',
            'فیزیک — قۆناغی سێیەم',
            'بیرکاری — تایبەت',
        ];

        $classrooms = collect($classroomNames)->map(
            fn ($name) => Classroom::create(['name' => $name])
        );

        // --- Assign teachers to classrooms ---
        $classrooms[0]->users()->attach([$teachers[0]->id, $teachers[1]->id]);
        $classrooms[1]->users()->attach([$teachers[2]->id, $teachers[3]->id]);
        $classrooms[2]->users()->attach([$teachers[4]->id]);
        $classrooms[3]->users()->attach([$teachers[0]->id]);

        // --- Enroll students into classrooms ---
        // Classroom 0: Math students
        $classrooms[0]->users()->attach(
            $students->whereIn('username', ['dilnia.omar', 'hawzhin.jalal', 'sarwar.ibrahim', 'shirin.baker', 'rawezh.tahir'])->pluck('id')
        );

        // Classroom 1: Computer students
        $classrooms[1]->users()->attach(
            $students->whereIn('username', ['aram.saeed', 'zhina.mahmud', 'lawek.rashid', 'harim.abdullah', 'sozan.najm'])->pluck('id')
        );

        // Classroom 2: Physics students
        $classrooms[2]->users()->attach(
            $students->whereIn('username', ['komal.yusuf', 'bina.hamid', 'awat.nuri', 'ribaz.khalid', 'gulala.husain'])->pluck('id')
        );

        // Classroom 3: mixed (some math + computer)
        $classrooms[3]->users()->attach(
            $students->whereIn('username', ['dilnia.omar', 'aram.saeed', 'shirin.baker', 'zhina.mahmud'])->pluck('id')
        );

        $this->command->info('✓ 5 teachers, 15 students, 4 classrooms seeded.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class StudentApiController extends Controller
{
    public function login()
    {

        $payload = [
            'username' => '120224216',
            'password' => '123456hks$',
        ];

        $response = Http::asForm()->post('https://quiztoxml.ucas.edu.ps/api/login', $payload);

        $data = $response->json();

        if (!$data['success']) {
            return response()->json([
                'message' => 'كلمة المرور او اسم المستخدم خطا'
            ]);
        }


        $studentId   = $data['data']['user_id'];
        $studentName = $data['data']['user_ar_name'];
        $token       = $data['Token'];


        $student = Student::updateOrCreate(
            ['student_number' => $studentId],
            [
                'student_name' => $studentName,
                'api_token'    => $token,
            ]
        );


        $tableResponse = Http::asForm()->post('https://quiztoxml.ucas.edu.ps/api/get-table', [
            'user_id' => $studentId,
            'token'   => $token,
        ]);

        $tableData = $tableResponse->json();

        if (!$tableData['success']) {
            return response()->json([
                'message' => 'فشل في جلب الجدول الدراسي'
            ]);
        }


        Schedule::where('student_id', $student->id)->delete();


        foreach ($tableData['data'] as $item) {

            $days = ['S', 'M', 'T', 'W', 'R'];

            foreach ($days as $day) {

                if (!empty($item[$day])) {

                    Schedule::create([
                        'student_id' => $student->id,

                        'subject_name' => $item['subject_name'],
                        'subject_code' => $item['subject_no'],
                        'teacher_name' => $item['teacher_name'],
                        'teacher_no'   => $item['teacher_no'],
                        'room_no'      => $item['room_no'],
                        'branch_no'    => $item['branch_no'],

                        'day'  => $day,
                        'time' => $item[$day],

                        'current_smtr'         => $item['current_SMTR'],
                        'exam_access'          => $item['exam_access'] ?? false,
                        'exam_access_message'  => $item['exam_access_message'] ?? null,
                        'online_code'          => $item['ONLINE_CODE'] ?? null,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'تم حفظ الجدول الدراسي بنجاح',
            'student' => $student,
            'total_rows' => Schedule::where('student_id', $student->id)->count()
        ]);
    }
}

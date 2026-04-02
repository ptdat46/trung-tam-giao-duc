<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Khối 6
            ['name' => 'Toán lớp 6 - Nền tảng', 'slug' => 'toan-lop-6-nen-tang', 'description' => 'Khóa học Toán lớp 6 dành cho học sinh trung bình, tập trung xây dựng nền tảng số học và hình học cơ bản.', 'price' => 499000],
            ['name' => 'Toán lớp 6 - Nâng cao', 'slug' => 'toan-lop-6-nang-cao', 'description' => 'Khóa học Toán lớp 6 dành cho học sinh khá - giỏi, mở rộng tư duy逻辑 và các bài toán chuyên sâu.', 'price' => 699000],
            ['name' => 'Vật lý lớp 6 - Nền tảng', 'slug' => 'vat-ly-lop-6-nen-tang', 'description' => 'Khóa học Vật lý lớp 6 dành cho học sinh mới tiếp cận môn Vật lý, tập trung các khái niệm cơ bản về cơ học và nhiệt học.', 'price' => 499000],

            // Khối 7
            ['name' => 'Toán lớp 7 - Nền tảng', 'slug' => 'toan-lop-7-nen-tang', 'description' => 'Khóa học Toán lớp 7 dành cho học sinh trung bình, nắm chắc đại số và hình học cơ sở.', 'price' => 499000],
            ['name' => 'Toán lớp 7 - Nâng cao', 'slug' => 'toan-lop-7-nang-cao', 'description' => 'Khóa học Toán lớp 7 dành cho học sinh khá - giỏi, rèn luyện các dạng toán thi học sinh giỏi.', 'price' => 699000],
            ['name' => 'Vật lý lớp 7 - Nền tảng', 'slug' => 'vat-ly-lop-7-nen-tang', 'description' => 'Khóa học Vật lý lớp 7 dành cho học sinh trung bình, tìm hiểu về điện học và quang học cơ bản.', 'price' => 499000],
            ['name' => 'Vật lý lớp 7 - Nâng cao', 'slug' => 'vat-ly-lop-7-nang-cao', 'description' => 'Khóa học Vật lý lớp 7 dành cho học sinh khá - giỏi, mở rộng kiến thức và bài tập nâng cao.', 'price' => 699000],
            ['name' => 'Tiếng Anh lớp 7 - Nền tảng', 'slug' => 'tieng-anh-lop-7-nen-tang', 'description' => 'Khóa học Tiếng Anh lớp 7 dành cho học sinh trung bình, tập trung ngữ pháp và từ vựng cơ bản.', 'price' => 499000],
            ['name' => 'Tiếng Anh lớp 7 - Nâng cao', 'slug' => 'tieng-anh-lop-7-nang-cao', 'description' => 'Khóa học Tiếng Anh lớp 7 dành cho học sinh khá - giỏi, phát triển kỹ năng giao tiếp và đọc hiểu.', 'price' => 699000],

            // Khối 8
            ['name' => 'Toán lớp 8 - Nền tảng', 'slug' => 'toan-lop-8-nen-tang', 'description' => 'Khóa học Toán lớp 8 dành cho học sinh trung bình, nắm vững đại số (phương trình, bất phương trình) và hình học.', 'price' => 499000],
            ['name' => 'Toán lớp 8 - Nâng cao', 'slug' => 'toan-lop-8-nang-cao', 'description' => 'Khóa học Toán lớp 8 dành cho học sinh khá - giỏi, chuyên sâu về chứng minh hình học và bài toán phương trình.', 'price' => 699000],
            ['name' => 'Vật lý lớp 8 - Nền tảng', 'slug' => 'vat-ly-lop-8-nen-tang', 'description' => 'Khóa học Vật lý lớp 8 dành cho học sinh trung bình, học về cơ học, nhiệt học và nguyên tử.', 'price' => 499000],
            ['name' => 'Vật lý lớp 8 - Nâng cao', 'slug' => 'vat-ly-lop-8-nang-cao', 'description' => 'Khóa học Vật lý lớp 8 dành cho học sinh khá - giỏi, các bài toán vận dụng cao và thí nghiệm thực hành.', 'price' => 699000],
            ['name' => 'Hóa học lớp 8 - Nền tảng', 'slug' => 'hoa-hoc-lop-8-nen-tang', 'description' => 'Khóa học Hóa học lớp 8 dành cho học sinh mới làm quen với môn Hóa, tập trung nguyên tử, nguyên tố và liên kết hóa học.', 'price' => 499000],

            // Khối 9
            ['name' => 'Toán lớp 9 - Nền tảng', 'slug' => 'toan-lop-9-nen-tang', 'description' => 'Khóa học Toán lớp 9 dành cho học sinh trung bình, chuẩn bị kiến thức ôn thi vào lớp 10.', 'price' => 699000],
            ['name' => 'Toán lớp 9 - Nâng cao', 'slug' => 'toan-lop-9-nang-cao', 'description' => 'Khóa học Toán lớp 9 dành cho học sinh khá - giỏi, luyện đề thi vào lớp 10 chuyên.', 'price' => 899000],
            ['name' => 'Vật lý lớp 9 - Nền tảng', 'slug' => 'vat-ly-lop-9-nen-tang', 'description' => 'Khóa học Vật lý lớp 9 dành cho học sinh trung bình, học về điện học, điện từ và quang học nâng cao.', 'price' => 699000],
            ['name' => 'Vật lý lớp 9 - Nâng cao', 'slug' => 'vat-ly-lop-9-nang-cao', 'description' => 'Khóa học Vật lý lớp 9 dành cho học sinh khá - giỏi, luyện tập các dạng bài thi vào lớp 10 chuyên.', 'price' => 899000],
            ['name' => 'Hóa học lớp 9 - Nền tảng', 'slug' => 'hoa-hoc-lop-9-nen-tang', 'description' => 'Khóa học Hóa học lớp 9 dành cho học sinh trung bình, nắm vững bảng tuần hoàn, liên kết ion - cộng hóa trị và phản ứng hóa học.', 'price' => 699000],

            // Khối 10
            ['name' => 'Toán lớp 10 - Nền tảng', 'slug' => 'toan-lop-10-nen-tang', 'description' => 'Khóa học Toán lớp 10 dành cho học sinh trung bình, tập trung vào đại số (bất đẳng thức, phương trình) và hình học phẳng.', 'price' => 699000],
            ['name' => 'Toán lớp 10 - Nâng cao', 'slug' => 'toan-lop-10-nang-cao', 'description' => 'Khóa học Toán lớp 10 dành cho học sinh khá - giỏi, phát triển tư duy toán học và kỹ năng giải bài tập chuyên sâu.', 'price' => 899000],
            ['name' => 'Vật lý lớp 10 - Nền tảng', 'slug' => 'vat-ly-lop-10-nen-tang', 'description' => 'Khóa học Vật lý lớp 10 dành cho học sinh trung bình, học về cơ học (động học, động lực học) và năng lượng.', 'price' => 699000],
            ['name' => 'Hóa học lớp 10 - Nền tảng', 'slug' => 'hoa-hoc-lop-10-nen-tang', 'description' => 'Khóa học Hóa học lớp 10 dành cho học sinh trung bình, ôn tập cấu tạo nguyên tử và hệ thống bảng tuần hoàn.', 'price' => 699000],

            // Khối 11
            ['name' => 'Toán lớp 11 - Nền tảng', 'slug' => 'toan-lop-11-nen-tang', 'description' => 'Khóa học Toán lớp 11 dành cho học sinh trung bình, tập trung giải tích (giới hạn, đạo hàm) và lượng giác.', 'price' => 699000],
            ['name' => 'Toán lớp 11 - Nâng cao', 'slug' => 'toan-lop-11-nang-cao', 'description' => 'Khóa học Toán lớp 11 dành cho học sinh khá - giỏi, chuyên sâu về dãy số, giới hạn và các bài toán tổ hợp.', 'price' => 899000],
            ['name' => 'Vật lý lớp 11 - Nền tảng', 'slug' => 'vat-ly-lop-11-nen-tang', 'description' => 'Khóa học Vật lý lớp 11 dành cho học sinh trung bình, học về điện trường, từ trường và cảm ứng điện từ.', 'price' => 699000],

            // Khối 12
            ['name' => 'Toán lớp 12 - Nền tảng', 'slug' => 'toan-lop-12-nen-tang', 'description' => 'Khóa học Toán lớp 12 dành cho học sinh trung bình, tập trung khảo sát hàm số, tích phân và số phức.', 'price' => 899000],
            ['name' => 'Toán lớp 12 - Nâng cao', 'slug' => 'toan-lop-12-nang-cao', 'description' => 'Khóa học Toán lớp 12 dành cho học sinh khá - giỏi, luyện thi THPT Quốc gia chuyên đề.', 'price' => 999000],
            ['name' => 'Vật lý lớp 12 - Nền tảng', 'slug' => 'vat-ly-lop-12-nen-tang', 'description' => 'Khóa học Vật lý lớp 12 dành cho học sinh trung bình, học về dao động cơ, sóng cơ, điện xoay chiều và quang học.', 'price' => 899000],
            ['name' => 'Vật lý lớp 12 - Nâng cao', 'slug' => 'vat-ly-lop-12-nang-cao', 'description' => 'Khóa học Vật lý lớp 12 dành cho học sinh khá - giỏi, luyện đề thi THPT Quốc gia chuyên đề.', 'price' => 999000],
            ['name' => 'Hóa học lớp 12 - Nền tảng', 'slug' => 'hoa-hoc-lop-12-nen-tang', 'description' => 'Khóa học Hóa học lớp 12 dành cho học sinh trung bình, tập trung hóa học hữu cơ và đại cương kim loại.', 'price' => 899000],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(
                ['slug' => $course['slug']],
                [
                    'name' => $course['name'],
                    'slug' => $course['slug'],
                    'description' => $course['description'],
                    'price' => $course['price'],
                    'thumbnail' => 'https://picsum.photos/seed/' . Str::slug($course['slug']) . '/800/400',
                ]
            );
        }
    }
}

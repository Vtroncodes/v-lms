<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Topic;
use App\Models\CourseLesson;
use App\Models\LessonTopic;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Create Courses
        $courseHtmlCss = Course::create([
            'title' => 'HTML and CSS 101',
            'description' => 'Introduction to HTML and CSS.',
            'level' => 'Beginner',
            'duration' => '4 weeks',
            'course_type' => 'recorded',
        ]);

        $coursePhp = Course::create([
            'title' => 'PHP Start',
            'description' => 'Introduction to PHP programming.',
            'level' => 'Beginner',
            'duration' => '6 weeks',
            'course_type' => 'recorded',
        ]);

        // Create 3 lessons for HTML and CSS 101 course
        $lessonHtml = Lesson::create(['title' => 'HTML Basics', 'description' => 'Learning the basics of HTML.']);
        $lessonCss = Lesson::create(['title' => 'CSS Basics', 'description' => 'Learning the basics of CSS.']);
        $lessonResponsive = Lesson::create(['title' => 'Responsive Design', 'description' => 'Learn how to make websites responsive.']);

        // Create 3 lessons for PHP Start course
        $lessonIntroPhp = Lesson::create(['title' => 'Introduction to PHP', 'description' => 'Understanding PHP basics.']);
        $lessonVariables = Lesson::create(['title' => 'PHP Variables', 'description' => 'Learn about variables in PHP.']);
        $lessonFunctions = Lesson::create(['title' => 'PHP Functions', 'description' => 'Learn about functions in PHP.']);

        // Create topics for lessons (3 topics per lesson)
        $topicHtmlIntro = Topic::create(['title' => 'HTML Tags', 'description' => 'Introduction to HTML tags.']);
        $topicCssSelectors = Topic::create(['title' => 'CSS Selectors', 'description' => 'Understanding CSS selectors.']);
        $topicResponsiveDesign = Topic::create(['title' => 'Media Queries', 'description' => 'How to use media queries for responsive design.']);

        $topicPhpIntro = Topic::create(['title' => 'PHP Syntax', 'description' => 'Understanding PHP syntax.']);
        $topicPhpVariables = Topic::create(['title' => 'Variable Types', 'description' => 'Different types of variables in PHP.']);
        $topicPhpFunctions = Topic::create(['title' => 'Function Definitions', 'description' => 'How to define functions in PHP.']);


       
    }
}

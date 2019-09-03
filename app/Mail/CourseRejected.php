<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Course;

class CourseRejected extends Mailable
{
    use Queueable, SerializesModels;

    private $course;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject( __("Lo sentimos :("))
                    ->markdown('emails.course_rejected')
                    ->with('course', $this->course);
        // return $this->view('view.name');
    }
}

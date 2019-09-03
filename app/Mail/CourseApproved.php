<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Course;

class CourseApproved extends Mailable
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
        return $this->subject( __("Felicidades"))
                    ->markdown('emails.course_approved')
                    ->with('course', $this->course);
        // return $this->view('view.name');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $otp)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('لقد طلبت إعادة تعيين كلمة المرور.')
            ->line('كود التحقق الخاص بك هو:')
            ->line("**{$this->otp}**")
            ->line('الكود صالح لمدة 15 دقيقة فقط.')
            ->line('إذا لم تقم بهذا الطلب، تجاهل هذه الرسالة.');
    }
}

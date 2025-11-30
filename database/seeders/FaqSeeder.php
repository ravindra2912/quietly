<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

       $data = [
           [
               'question' => 'What is Quietly?',
               'answer' => 'Quietly is a platform that offers a variety of services to enhance your daily life.'
           ],
           [
               'question' => 'How many groups can I create?',
               'answer' => 'There is no limit. You can create as many groups as your routines require—work shifts, global clients, family members, etc.'
           ],
           [
               'question' => 'Can I activate multiple groups at once?',
               'answer' => 'Yes. When multiple groups are active, their rules are merged intelligently, always keeping the most permissive setting for included contacts.'
           ],
           [
               'question' => 'What happens after a timer expires?',
               'answer' => 'Once the countdown ends, Quietly returns your phone to its default notification profile so you never leave modes running accidentally.'
           ],
           [
               'question' => 'Is my contact data secure?',
               'answer' => 'All processing stays on-device. Quietly never uploads your contacts, call history, or notification data to the cloud.'
           ],
           [
               'question' => 'Can I set different ringtones per group?',
               'answer' => 'Absolutely. Assign custom ringtones, vibrations, or even different LED colors where supported to instantly recognize who’s calling.'
           ],
           [
               'question' => 'What permissions do you need?',
               'answer' => 'Only the essentials: Contacts (for grouping), Phone state (to detect calls), and Notification access (to manage volume and alerts). You can revoke any permission at any time.'
           ],
           [
               'question' => 'How can I contact customer support?',
               'answer' => 'You can reach our customer support team via email at '. config('const.contactUs.email') .' or call us at +91 '. config('const.contactUs.contact')
           ],
        ];

        foreach ($data as $faq) {
            Faq::create($faq);
        }
    }
}

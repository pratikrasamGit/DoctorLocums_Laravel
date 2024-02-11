@component('mail::message')

**@lang('Dear :name,', [ 'name' =>  $first_name])**

@lang('Whether you are recuperating at home, taking a break, or just getting off a 12, thank you for completing your Nurseify introductory profile.')  

@lang('In order for your profile to be made available for healthcare facilities to view, there are a few more pieces of information we need.')

@lang('Please <a href="https://app.nurseify.io">click here</a> to complete your full profile.')

@lang('We are validating your account with the information you have provided. You will receive another confirmation email from Nurseify when your account has been verified.')

**@lang('Nurseify Referral Program #ItTakesAVillage')**

@lang('Lastly, if you have not already done so, we welcome your support in spreading the word. You will receive $5 for every nurse you refer that creates a Nurseify account, and you will receive $1,000 if the nurse you refer works 600 hours within the first 12 months!')

@component('mail::button', ['url' => 'https://nurseify.app/referral-program'])
@lang('Click here to start earning some extra cash.')
@endcomponent

@lang('Thank you,')  
@lang('Team Nurseify')  

@lang('**Please do not reply to this email.**')

@endcomponent

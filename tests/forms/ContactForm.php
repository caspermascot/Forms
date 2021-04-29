<?php


namespace FormTests\forms;


use Casper\Fields\CharField;
use Casper\Fields\ChoiceField;
use Casper\Fields\Choices;
use Casper\Fields\EmailField;
use Casper\Fields\Fields;
use Casper\Fields\ListField;
use Casper\Fields\TextField;
use Casper\Fields\UrlField;
use Casper\Forms;

class ContactForm extends Forms
{
    public CharField $firstName;
    public EmailField $email;
    public UrlField $website;
    public TextField $message;
    public ChoiceField $contactType;
    public ListField $listField;

    protected function build(): void
    {
        $this->firstName = $this->charField()
            ->allowBlank(true)
            ->allowNull(true)
            ->required(false);
        $this->email = $this->emailField()->required(false);
        $this->website = $this->urlField();
        $this->message = $this->textField()->minLength(15);
        $this->contactType = $this->choiceField()->choices(['enquiry','info'])->default('info');
        $this->listField = $this->listField()->type('integer')->required(false);
    }
}
<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use App\Model\CategoryModel;
use App\Model\NewModel;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;
use Nette\Mail\SmtpMailer;
use Nette\Neon\Neon;

class HomepagePresenter extends BasePresenter
{
    /** @inject */
    public CategoryModel $categoryModel;

    /** @inject */
    public NewModel $newModel;


    public function renderDefault(): void
    {
        $this->template->news = $this->repository->get3NewsByCategory('Novinky');

        $today = date('Y-m-d');
        $this->template->futureEvents = $this->newModel->getFutureEvents($today);
        $this->template->pastEvents = $this->newModel->getPastEvents($today)->limit(1);




    }

    public function renderReservation(): void
    {

    }

    public function createComponentContactForm(): Form
    {
        $form = new Form();

        $form->addHidden('protiBotum');

        $form->addText('name', 'Jméno a příjmení')
            ->addRule(Form::MAX_LENGTH, 'Maximální délka je %s znaků', 100)
            ->setRequired('Musíte zadat Vaše jméno a příjmení.');

        $form->addEmail('email', 'Email')
            ->addRule(Form::MAX_LENGTH, 'Maximální délka je %s znaků', 150)
            ->setRequired('Musíte zadat Váš email.');

        $form->addTextArea('message', 'Zpráva')
            ->addRule($form::MAX_LENGTH, 'Zpráva je příliš dlouhá', 5000)
            ->setRequired('Obsah zprávy nemůže zůstat prázdný.');

        $form->addSubmit('submit', 'Odeslat zprávu');

        $form->onSubmit[] = function (Form $form) {
            try {
                $values = $form->getValues();

                bdump($values);
                if ($values['protiBotum'] === "") {

                    $mail = new Message();

                    $vars = $this->configuration->getAllVars();
                    if (isset($vars['email']))
                        $ownersEmail = $vars['email'];
                    else
                        $ownersEmail = 'info@filipurban.cz';

                    $mail->setFrom($values['email'], $values['name'])
                        ->addTo($ownersEmail)
                        ->setSubject('JógaŽatec.cz - zpráva z kontaktního formuláře')
                        ->setBody($values['message']);

                    $parameters = Neon::decode(file_get_contents(__DIR__ . "/../../config/server/local.neon"));

                    $mailer = new SmtpMailer([
                        'host' => $parameters['mail']['host'],
                        'username' => $parameters['mail']['username'],
                        'password' => $parameters['mail']['password'],
                        'secure' => $parameters['mail']['secure'],
                    ]);

                    $mailer->send($mail);

                    $this->flashMessage('Email byl úspěšně odeslán!', 'info');

                    if ($this->isAjax()) {
                        $this->redrawControl('flashes');
                        $this->redrawControl('form');
                        $form->setValues([], TRUE);
                    } else {
                        $this->redirect('this#kontakt');
                    }
                } else {
                    $this->flashMessage('Boti nic posílat nesmějí!', 'danger');

                    if ($this->isAjax()) {
                        $this->redrawControl('flashes');
                        $this->redrawControl('form');
                        $form->setValues([], TRUE);
                    } else {
                        $this->redirect('this#kontakt');
                    }
                }
            } catch (SmtpException $e) {
                $this->flashMessage('Vaši zprávu se nepodařilo odeslat. Kontaktujte prosím správce webu na info@filipurban.cz', 'danger');
            }
        };

        return $form;
    }
}

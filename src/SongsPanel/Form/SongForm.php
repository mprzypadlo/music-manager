<?php
namespace App\SongsPanel\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of SongForm
 *
 * @author marek
 */
class SongForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, ['label' => 'Nazwa piosenki'])
                ->add('url', null, ['label' => 'Link do Youtube'])
                ->add('Zapisz', SubmitType::class);
    }
    
}

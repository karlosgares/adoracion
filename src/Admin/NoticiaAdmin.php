<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Noticia;
use App\Form\Type\ImageType;

final class NoticiaAdmin extends AbstractAdmin
{
	public function toString($entity) {
		return 'Noticia';
	}

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        	->add('fechaalta',null, ['label'=> 'Fecha alta'], [], DatePickerType::class)
        	->add('titulo')
			->add('activo')
			->add('portada')
			;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
			->add('fechaalta', null, ['label'=> 'Fecha alta', 'format'=> "d-m-Y"])
			->add('titulo', null, ['label'=> 'Título'])
			->add('activo')
			->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        if (!($this->getSubject()->getId()>0)) {
        	$this->getSubject()->setActivo(true);
        }

        $formMapper
        	->with('Datos', ['class' => 'col-xs-12 col-md-6'])
			->add('fechaalta', DatePickerType::class, [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_collapse'           => true,
                    'dp_calendar_weeks'     => false,
                    'dp_view_mode'          => 'days',
                    'dp_min_view_mode'      => 'days',
                    'label' => 'Fecha alta'
            ])
			->add('fechabaja', DatePickerType::class, [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_collapse'           => true,
                    'dp_calendar_weeks'     => false,
                    'dp_view_mode'          => 'days',
                    'dp_min_view_mode'      => 'days',
                    'label' => 'Fecha baja', 'required' => false
            ])
			->add('titulo', null, ['label'=> 'Título'])
			->add('contenido', TextareaType::class, ['label'=> 'Contenido', 'required' => false, 'attr'=> ['rows' => 10, 'class' => 'tinymce']])
			->add('activo')
			->add('portada')
			->end()
			->with('Foto', ['class' => 'col-xs-12 col-md-6'])
			->add('fotolocation', FileType::class,['label' => 'Subir foto', 'mapped' => false, 'required' => false] )
			->add('posicion', ChoiceType::class, ['label' => 'Posición de la foto', 'choices'=> array_flip(Noticia::getPosiciones()), 'required' => false])
			->add('foto', ImageType::class, ['label' => false])
			
			->end()
		;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
			->add('id')
			->add('fechabaja')
			->add('foto')
			->add('activo')
			->add('portada')
			;
    }

    public function prePersist($entity) {
    	if (!is_null($this->getForm()->get('fotolocation')->getData())) {
    		$file = $this->getForm()->get('fotolocation')->getData();
    		$entity->upload($file);
    	}
    }

    public function preUpdate($entity) {
    	$this->prePersist($entity);
    }

    public function getTemplate($name)
	{
	    switch ($name) {
	        case 'show':
	            return 'crud/show_noticia.html.twig';
	            break;
	        default:
	            return parent::getTemplate($name);
	            break;
	    }
	}
}

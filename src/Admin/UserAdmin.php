<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('email')
            ->add('roles')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('email')
            ->add('rolesAsString', null, ['label' => 'Roles'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $choices = ['admin' => 'admin', 'cliente' =>'cliente'];
        $form
            //->add('id')
            ->add('email')
            ->add('roles', ChoiceType::class,[
                'multiple' => true,
                'choices' => $choices
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {   
        $show
            ->add('id')
            ->add('email')
            ->add('rolesAsString', null, ['label' => 'Roles'])
        ;
    }
}

<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BannerAdmin extends Admin
{
    /**
     * Fields to be shown on create/edit forms
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('Banner');
        $formMapper->add('title', 'text');
        $formMapper->add('path', 'text', array('read_only' => true));
        $formMapper->add('file', 'file', array('required' => false));
        $formMapper->end();

        $formMapper->with('Relations');
        $formMapper->add('category');
        $formMapper->add('brand');
        $formMapper->end();
    }

    /**
     * Fields to be shown on filter forms
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');
        $datagridMapper->add('title');
        $datagridMapper->add('category');
        $datagridMapper->add('brand');
    }

    /**
     * Fields to be shown on lists
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('category');
        $listMapper->addIdentifier('brand');
        $listMapper->add('webPath');
    }

}

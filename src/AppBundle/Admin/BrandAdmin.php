<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BrandAdmin extends Admin
{
    /**
     * Fields to be shown on create/edit forms
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('Brand');
        $formMapper->add('name', 'text');
        $formMapper->add('slug', 'text');
        $formMapper->end();

        $formMapper->with('Size');
        $formMapper->add('width');
        $formMapper->add('height');
        $formMapper->end();
    }

    /**
     * Fields to be shown on filter forms
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
        $datagridMapper->add('slug');
    }

    /**
     * Fields to be shown on lists
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('slug');
        $listMapper->addIdentifier('width');
        $listMapper->addIdentifier('height');
    }

}

<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * ModulosController
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 *
 * @Route("/admin/modulos")
 */
class ModulosController extends Controller
{

    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="admin_modulos_index")
     */
    public function indexAction(Request $request)
    {
        $service = new \App\Service\ModuleService();
        $modules = array_map(function ($value) {
            $module = new $value['class'];
            
            return [
                'active' => $value['active'],
                'key' => $module->getKeyName(),
                'name' => $module->getDisplayName(),
            ];
        }, $service->getModules());
        
        return $this->render('admin/modulos/index.html.twig', [
            'tab' => 'modulos',
            'modules' => $modules
        ]);
    }

    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/update", name="admin_modulos_update")
     */
    public function updateAction(Request $request)
    {
        $key = $request->get('key');
        $active = $request->get('active');
        
        $service = new \App\Service\ModuleService();
        $service->update($key, $active);
        
        return $this->json([
            'ok'
        ]);
    }
}
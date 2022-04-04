<?php
/**
 * Created by PhpStorm.
 * User: ecruz
 * Date: 19/03/2022
 * Time: 12:03
 */

namespace App\Controller;


use App\Entity\ActorEconomico;
use App\Entity\Empresa;
use App\Repository\EmpresaRepository;

class Permisos
{

    public function decode_link($link){
        $cod = base64_decode($link);
        $codx = mb_split('-',$cod);
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($codx[0]);
        if (is_null($empresa))
            return null;

        $actorE = $this->getDoctrine()->getRepository(ActorEconomico::class)->findOneBy(['codigo'=>$codx[1]]);
        if (is_null($actorE))
            return null;

        return ['id'=>$codx[0],'codigo'=>$codx[1]];

    }
    public function has_perm($user,$empresa){
        if (is_null($empresa))
            return false;
        return $user->getEmpresa()->getId() == $empresa->getId();

    }
}
<?php

namespace Src\Model\SQL;


class TipoEquipamentoSQL
{

    public static function InserirTipo()
    {
        $sql = 'INSERT into tb_tipoequip (nome) VALUES (?)';
        return $sql;
    }

    public static function RetornarTiposEquipamentos()
    {
        $sql = 'SELECT id,nome
                    FROM tb_tipoequip';
        return $sql;
    }

    public static function ConsultarTipoEquipamentoSQL($filtro_palavra)
    {
        $sql = 'SELECT id, nome
        FROM tb_tipoequip';

        if (!empty($filtro_palavra))
            $sql = $sql . ' WHERE nome LIKE ?';

        return $sql;
    }

    public static function FiltrarTipoEquipamentoSQL($filtro_palavra)
    {

        $sql = 'SELECT id, nome 
        FROM tb_tipoequip';

        if (!empty($filtro_palavra))
            $sql = $sql . ' WHERE nome LIKE ?';

        return $sql;
    }

    public static function AlterarTipoEquipamentoSQL()
    {
        $sql = 'UPDATE tb_tipoequip set nome = ?
                            WHERE id = ?';
        return $sql;
    }

    public static function ExcluirTipoEquipamentoSQL()
    {
        $sql = 'DELETE FROM tb_tipoequip WHERE id = ?';
        return $sql;
    }

    public static function DADOS_EMPRESA_SQL()
    {
        $sql = 'SELECT EmpNome, EmpCNPJ, EmpLogoPath, EmpEnd, EmpCidade, EmpNumero  FROM tb_empresa WHERE EmpID = ?';
        return $sql;
    }
}

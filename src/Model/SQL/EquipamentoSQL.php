<?php

namespace Src\Model\SQL;



class EquipamentoSQL
{
    public static function InserirEquipamentoSQL()
    {
        $sql = 'INSERT into tb_equipamento (identificacao, descricao, tipoequip_id, modeloequip_id, equipamento_EmpID) VALUES (?,?,?,?,?)';
        return $sql;
    }

    public static function AlterarEquipamentoSQL()
    {
        $sql = 'UPDATE tb_equipamento set identificacao = ?, descricao = ?, tipoequip_id = ?, modeloequip_id = ? WHERE id = ?';
        return $sql;
    }

    public static function ConsultarEquipamentoSQL()
    {
        $sql = 'SELECT tb_equipamento.id as idEquip, identificacao, descricao, tb_tipoequip.id as idTipo,
                       tb_modeloequip.id as idModelo, tb_tipoequip.nome as nomeTipo, tb_modeloequip.nome as nomeModelo
                            FROM tb_equipamento 
                                inner join tb_tipoequip on 
                                    tb_equipamento.tipoequip_id = tb_tipoequip.id	
                                inner join tb_modeloequip on 
                                    tb_equipamento.modeloequip_id = tb_modeloequip.id ';
        return $sql;
    }

    public static function FiltrarEquipamentoSQL($nome_filtro)
    {

        $sql = ' SELECT equip.id as idEquip,
                        tb_tipoequip.nome as nomeTipo,
                        tb_modeloequip.nome as nomeModelo,
                        equip.identificacao as identificacao,
                        equip.descricao as descricao,
                        status_equipamento,
                (SELECT GROUP_CONCAT(prd.ProdDescricao) FROM tb_equipamento_insumo as ei
              LEFT JOIN tb_produto as prd
                     ON prd.ProdID = ei.produto_ProdID WHERE ei.equipamento_id = equip.id) as nome_produto,
                (SELECT GROUP_CONCAT(se.ServNome) FROM tb_equipamento_servico as si
              LEFT JOIN tb_servico as se
                     ON se.ServID = si.servico_ServID WHERE si.equipamento_id = equip.id) as nome_servico
                   FROM tb_equipamento as equip
             INNER JOIN tb_tipoequip 
                     ON equip.tipoequip_id = tb_tipoequip.id
             INNER JOIN tb_modeloequip 
                     ON equip.modeloequip_id = tb_modeloequip.id';
        
        $sql .= " AND equipamento_EmpID = ?";

        if (!empty($nome_filtro))
            $sql = $sql . ' WHERE descricao LIKE ?';

        return $sql;
    }
    public static function AlocarEquipamento()
    {
        $sql = 'INSERT INTO tb_alocar (situacao, data_alocacao, equipamento_id, setor_id) VALUES (?,?,?,?)';
        return $sql;
    }

    public static function DetalharEquipamentoSQL()
    {
        $sql = 'SELECT id, identificacao, descricao, tipoequip_id, modeloequip_id
                    FROM tb_equipamento WHERE id = ?';
        return $sql;
    }
    public static function RetornarEquipamentoSQL()
    {
        $sql = 'SELECT id, identificacao, descricao, tipoequip_id, modeloequip_id
                    FROM tb_equipamento';
        return $sql;
    }
    public static function SelecionarEquipamentoNaoAlocado()
    {
        $sql = 'SELECT ti.nome as nome_tipo,
                       mo.nome as nome_modelo,
                       eq.identificacao,
                       eq.id
                    FROM tb_equipamento as eq
                    INNER JOIN tb_tipoequip as ti
                        ON eq.tipoequip_id = ti.id
                    INNER JOIN tb_modeloequip as mo
                        ON eq.modeloequip_id = mo.id
                    WHERE eq.id NOT IN (SELECT equipamento_id 
                                                FROM tb_alocar
                                                    WHERE situacao != ?)';

        return $sql;
    }

    public static function SelecionarEquipamentoAlocado($situacao)
    {
        $sql = 'SELECT ti.nome as nome_tipo,
                        mo.nome as nome_modelo,
                        eq.identificacao,
                        eq.descricao,
                        al.id as id_alocar,
                        st.nome_setor as nome_setor,
                        al.situacao
                            FROM tb_alocar as al
                        INNER JOIN tb_equipamento as eq
                                ON 	al.equipamento_id = eq.id
                        INNER JOIN tb_tipoequip as ti
                                ON eq.tipoequip_id = ti.id
                        INNER JOIN tb_modeloequip as mo
                                ON eq.modeloequip_id = mo.id
                                INNER JOIN tb_setor as st
                                ON al.setor_id = st.id
                       ';

        if (!empty($situacao)) {
            $sql .= ' WHERE eq.descricao = ?';
        }

        return $sql;
    }
    public static function ConsultarEquipamentoBuscaSQL($BuscarTipo)
    {
        $sql = " SELECT equip.id as idEquip,
                        tb_tipoequip.nome as nomeTipo,
                        tb_tipoequip.id as idTipo,
                        tb_modeloequip.id as idModelo,
                        tb_modeloequip.nome as nomeModelo,
                        equip.identificacao as identificacao,
                        equip.descricao as descricao,
                        status_equipamento,
                (SELECT GROUP_CONCAT(prd.ProdDescricao) FROM tb_equipamento_insumo as ei
              LEFT JOIN tb_produto as prd
                     ON prd.ProdID = ei.produto_ProdID WHERE ei.equipamento_id = equip.id) as nome_produto,
                (SELECT GROUP_CONCAT(se.ServNome) FROM tb_equipamento_servico as si
              LEFT JOIN tb_servico as se
                     ON se.ServID = si.servico_ServID WHERE si.equipamento_id = equip.id) as nome_servico
                   FROM tb_equipamento as equip
             INNER JOIN tb_tipoequip 
                     ON equip.tipoequip_id = tb_tipoequip.id
             INNER JOIN tb_modeloequip 
                     ON equip.modeloequip_id = tb_modeloequip.id";

        switch ($BuscarTipo) {
            case FILTRO_TIPO:
                $sql .= ' WHERE tb_tipoequip.nome LIKE ?';
                break;
            case FILTRO_IDENTIFICACAO:
                $sql .= ' WHERE identificacao LIKE ?';
                break;
            case FILTRO_DESCRICAO:
                $sql .= ' WHERE descricao LIKE ?';
                break;
            case FILTRO_MODELO:
                $sql .= ' WHERE tb_modeloequip.nome LIKE ?';
                break;
        }
        $sql .= ' AND equipamento_EmpID = ?';
        return $sql;
    }
    public static function ConsultarEquipamento()
    {
        $sql = 'SELECT  equip.id as idEquip,
                        tb_tipoequip.nome as nomeTipo,
                        tb_tipoequip.id as idTipo,
                        tb_modeloequip.id as idModelo,
                        tb_modeloequip.nome as nomeModelo,
                        equip.identificacao as identificacao,
                        equip.descricao as descricao,
                        status_equipamento,
                (SELECT GROUP_CONCAT(prd.ProdDescricao) FROM tb_equipamento_insumo as ei
              LEFT JOIN tb_produto as prd
                     ON prd.ProdID = ei.produto_ProdID WHERE ei.equipamento_id = equip.id) as nome_produto,
                (SELECT GROUP_CONCAT(se.ServNome) FROM tb_equipamento_servico as si
              LEFT JOIN tb_servico as se
                     ON se.ServID = si.servico_ServID WHERE si.equipamento_id = equip.id) as nome_servico
                   FROM tb_equipamento as equip
             INNER JOIN tb_tipoequip 
                     ON equip.tipoequip_id = tb_tipoequip.id
             INNER JOIN tb_modeloequip 
                     ON equip.modeloequip_id = tb_modeloequip.id
                  WHERE equipamento_EmpID = ?';

        return $sql;
    }

    public static function ExcluirEquipamentoSQL()
    {
        $sql = 'DELETE FROM tb_equipamento WHERE id = ?';
        return $sql;
    }

    public static function RemoverAlocamentoSQL()
    {
        $sql = 'UPDATE tb_alocar set situacao = ?, data_remocao = ? 
                    WHERE id = ?';

        return $sql;
    }

    public static function EQUIPAMENTOS_SETOR_LOGADO_API()
    {
        $sql = 'SELECT ti.nome as nome_tipo,
                        mo.nome as nome_modelo,
                        eq.identificacao,
                        eq.descricao,
                        al.id as id_alocar
                            FROM tb_alocar as al
                        INNER JOIN tb_equipamento as eq
                                ON 	al.equipamento_id = eq.id
                        INNER JOIN tb_tipoequip as ti
                                ON eq.tipoequip_id = ti.id
                        INNER JOIN tb_modeloequip as mo
                                ON eq.modeloequip_id = mo.id
                        WHERE al.setor_id = ?
                        AND al.situacao = ? ORDER BY nome_modelo';
        return $sql;
    }

    public static function SELECT_SERVICO_PARA_EQUIPAMENTO_SQL()
    {
        $sql = 'SELECT ServID, ServNome FROM tb_servico WHERE ServEmpID = ?';
        return $sql;
    }

    public static function LIST_SERVICO_DO_EQUIPAMENTO_SQL()
    {
        $sql = 'SELECT ServID, 
                       ServNome,
                       equipamento_id,
                       id 
                  FROM tb_equipamento_servico 
            INNER JOIN tb_servico
                    ON ServID = servico_ServID
                 WHERE equipamento_id = ?';
        return $sql;
    }

    public static function SELECT_PRODUTO_PARA_EQUIPAMENTO_SQL()
    {
        $sql = 'SELECT ProdID, ProdDescricao FROM tb_produto WHERE ProdEmpID = ?';
        return $sql;
    }

    public static function LIST_PRODUTO_DO_EQUIPAMENTO_SQL()
    {
        $sql = 'SELECT ProdID, 
                       ProdDescricao,
                       equipamento_id,
                       id 
                  FROM tb_equipamento_insumo 
            INNER JOIN tb_produto
                    ON ProdID = produto_ProdID
                 WHERE equipamento_id = ?';
        return $sql;
    }

    public static function UPDATE_STATUS_EQUIPAMENTO_SQL()
    {
        $sql = 'UPDATE tb_equipamento SET status_equipamento = ? WHERE id = ? AND equipamento_EmpID = ?';
        return $sql;
    }

    public static function INSERT_SERVICO_PARA_EQUIPAMENTO_SQL()
    {
        $sql = 'INSERT INTO tb_equipamento_servico (equipamento_id, servico_ServID) VALUE (?,?)';
        return $sql;
    }

    public static function INSERT_PRODUTO_PARA_EQUIPAMENTO_SQL()
    {
        $sql = 'INSERT INTO tb_equipamento_insumo (produto_ProdID, equipamento_id) VALUE (?,?)';
        return $sql;
    }

    public static function DELETE_PRODUTO_PARA_EQUIPAMENTO_SQL()
    {
        $sql = 'DELETE FROM tb_equipamento_insumo WHERE id = ?';
        return $sql;
    }

    public static function DELETE_SERVICO_PARA_EQUIPAMENTO_SQL()
    {
        $sql = 'DELETE FROM tb_equipamento_servico WHERE id = ?';
        return $sql;
    }
}

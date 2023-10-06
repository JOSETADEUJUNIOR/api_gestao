<?php

namespace Src\Model\SQL;


class LoteSQL
{

    public static function InserirLote()
    {
        $sql = 'INSERT into tb_lote (numero_lote, empresa_EmpID, data_criacao) VALUES (?,?,?)';
        return $sql;
    }

    public static function InserirLoteInsumo()
    {
        $sql = 'INSERT into tb_lote_insumo (valor, quantidade, lote_equip_id, produto_ProdID) VALUES (?,?,?,?)';
        return $sql;
    }

    public static function InserirLoteServico()
    {
        $sql = 'INSERT into tb_lote_servico (valor, quantidade, lote_equip_id, servico_ServID) VALUES (?,?,?,?)';
        return $sql;
    }

    public static function InativarLoteSQL()
    {
        $sql = 'UPDATE tb_lote set status = ? WHERE id = ? and empresa_EmpID = ?';
        return $sql;
    }

    public static function ReabrirLoteSQL()
    {
        $sql = 'UPDATE tb_lote set status = ? WHERE id = ? and empresa_EmpID = ?';
        return $sql;
    }

    public static function InserirLoteEquip()
    {
        $sql = 'INSERT into tb_lote_equip (id_lote, equipamento_id, numero_serie, numero_versao) VALUES (?,?,?,?)';
        return $sql;
    }

    public static function VerificaLoteChamado()
    {
        $sql = 'Select lote_id From tb_chamado Where lote_id = ? And empresa_empID = ?';
        return $sql;
    }

    public static function FiltrarLoteSQL($empresa, $filtro, $status)
{
    $sql = "SELECT lt.id, lt_eq.id_lote_equip, eq.descricao, lt.numero_lote, lt.valor_total, lt.status, lt.data_criacao
    FROM tb_lote lt
    INNER JOIN tb_lote_equip lt_eq ON lt.id = lt_eq.id_lote
    INNER JOIN tb_equipamento eq ON lt_eq.equipamento_id = eq.id
    WHERE empresa_EmpID = '$empresa'";

    if (!empty($filtro)) {
        $sql .= " AND numero_lote Like '%$filtro%'";
    }
    if (!empty($status)) {
        if ($status=="A") {
            $sql .= " AND status = 'A'";
        }elseif($status=="N"){
            $sql .= " AND status = 'N'";
        }elseif($status=="E"){
            $sql .= " AND status = 'E'";
        }else {
            $sql .= " AND status In('A','N','E')";
        }
    }

    $sql .= ' GROUP BY id';
    return $sql;
}

public static function RetornaLoteSQL()
{
    $sql = "Select * from tb_lote Where status='E' And id Not IN (Select Distinct lote_id From tb_chamado)";
    return $sql;
}

    public static function CalculaTotalLoteSQL()
    {
        $sql = "SELECT
            e.descricao AS descricao,
            e.id AS equipamento_id,
            le.numero_serie AS numero_serie_equipamento,
            i.quantidade AS qtd_insumo,
            i.valor AS valor_insumo,
            s.quantidade AS qtd_servico,
            s.valor AS valor_servico,
            (i.quantidade * i.valor) AS total_insumos,
            (s.quantidade * s.valor) AS total_servicos,
            (i.quantidade * i.valor) + (s.quantidade * s.valor) AS total_geral
    FROM
        tb_lote AS l
        INNER JOIN tb_lote_equip AS le ON l.id = le.id_lote
        INNER JOIN tb_equipamento AS e ON le.equipamento_id = e.id
        LEFT JOIN tb_lote_insumo AS i ON le.id_lote_equip = i.lote_equip_id
        LEFT JOIN tb_lote_servico AS s ON le.id_lote_equip = s.lote_equip_id
    WHERE
        l.id = ?";

        return $sql;
    }
  


    public static function ConsultarInsumoServicoLoteSQL()
    {
        $sql = 'SELECT e.id_lote_equip, e.equipamento_id, e.numero_serie, e.numero_versao, i.valor AS insumo_valor, i.quantidade AS insumo_quantidade, p.ProdID, p.ProdDescricao, s.valor AS servico_valor, s.quantidade AS servico_quantidade, se.ServID, se.ServNome
        FROM tb_lote_equip e
        LEFT JOIN tb_lote_insumo i ON e.id_lote_equip = i.lote_equip_id
        LEFT JOIN tb_produto p ON i.produto_ProdID = p.ProdID
        LEFT JOIN tb_lote_servico s ON e.id_lote_equip = s.lote_equip_id
        LEFT JOIN tb_servico se ON s.servico_ServID = se.ServID
        WHERE e.id_lote_equip = ?;';

        return $sql;
    }

    public static function ConsultarServicosLoteSQL()
    {
        $sql = 'SELECT e.id_lote_equip, e.equipamento_id, e.numero_serie, e.numero_versao, s.valor AS servico_valor, s.quantidade AS servico_quantidade, se.ServID, se.ServNome
    FROM tb_lote_equip e
    LEFT JOIN tb_lote_servico s ON e.id_lote_equip = s.lote_equip_id
    LEFT JOIN tb_servico se ON s.servico_ServID = se.ServID
    WHERE e.id_lote_equip = ?;';

        return $sql;
    }

    public static function ConsultarProdutosLoteSQL()
    {
        $sql = 'SELECT e.id_lote_equip, e.equipamento_id, e.numero_serie, e.numero_versao, i.valor AS insumo_valor, i.quantidade AS insumo_quantidade, p.ProdID, p.ProdDescricao
    FROM tb_lote_equip e
    LEFT JOIN tb_lote_insumo i ON e.id_lote_equip = i.lote_equip_id
    LEFT JOIN tb_produto p ON i.produto_ProdID = p.ProdID
    WHERE e.id_lote_equip = ?;';

        return $sql;
    }


    public static function FiltrarEquipamentoLote()
    {
        $sql = 'SELECT 
            l.id AS lote_id,
            l.numero_lote,
            l.valor_total,
            e.id_lote_equip AS id_lote_equip,
            e.equipamento_id,
            te.identificacao,
            e.numero_serie,
            e.numero_versao,
            COUNT(li.lote_equip_id) AS num_insumos,
            COUNT(ls.lote_equip_id) AS num_servicos
        FROM tb_lote l
        INNER JOIN tb_lote_equip e ON l.id = e.id_lote
        INNER JOIN tb_equipamento te ON e.equipamento_id = te.id
        LEFT JOIN tb_lote_insumo li ON e.id_lote_equip = li.lote_equip_id
        LEFT JOIN tb_lote_servico ls ON e.id_lote_equip = ls.lote_equip_id
        WHERE l.id = ?
        GROUP BY l.id, e.id_lote_equip;';

        return $sql;
    }


    public static function EditarEquipamentoLoteSQL()
    {
        $sql = 'UPDATE tb_lote_equip set numero_serie = ?, numero_versao = ? WHERE id_lote_equip = ?';
        return $sql;
    }



    /*   SELECT 
        l.id AS lote_id,
        l.numero_lote,
        l.valor_total,
        e.id_lote_equip,
        e.equipamento_id,
        e.numero_serie,
        e.numero_versao,
        i.valor AS insumo_valor,
        i.quantidade AS insumo_quantidade,
        p.ProdID,
        p.ProdDescricao,
        s.valor AS servico_valor,
        s.quantidade AS servico_quantidade,
        se.ServID, 
        se.ServNome
        FROM tb_lote l
        INNER JOIN tb_lote_equip e ON l.id = e.id_lote
        LEFT JOIN tb_lote_insumo i ON e.id_lote_equip = i.lote_equip_id
        LEFT JOIN tb_produto p ON i.produto_ProdID = p.ProdID
        LEFT JOIN tb_lote_servico s ON e.id_lote_equip = s.lote_equip_id
        LEFT JOIN tb_servico se ON s.servico_ServID = se.ServID
        WHERE l.id = ? */



    public static function ConsultarInsumoSQL()
    {
        $sql = 'SELECT
        tb_equipamento.id,
        tb_equipamento.identificacao,
        tb_equipamento.descricao,
        tb_produto.ProdID,
        tb_produto.ProdDescricao,
        tb_produto.ProdValorVenda,
        tb_produto.ProdEstoque
      FROM
        tb_equipamento
      LEFT JOIN
        tb_equipamento_insumo ON tb_equipamento.id = tb_equipamento_insumo.equipamento_id
      LEFT JOIN
        tb_produto ON tb_equipamento_insumo.produto_ProdID = tb_produto.ProdID
      WHERE
        tb_equipamento.id = ?;';

        return $sql;
    }

    public static function ConsultarServicoLoteSQL()
    {
        $sql = ' SELECT
        tb_equipamento.id,
        tb_equipamento.identificacao,
        tb_equipamento.descricao,
        tb_servico.ServID,
        tb_servico.ServNome,
        tb_servico.ServDescricao,
        tb_servico.ServValor
      FROM
        tb_equipamento
      LEFT JOIN
        tb_equipamento_servico ON tb_equipamento.id = tb_equipamento_servico.equipamento_id
      LEFT JOIN
        tb_servico ON tb_equipamento_servico.servico_ServID = tb_servico.ServID
      WHERE
        tb_equipamento.id = ?;';

        return $sql;
    }



    public static function ConsultarSaldoProdutoSQL()
    {
        $sql = 'SELECT ProdEstoque 
                    FROM tb_produto
                        WHERE ProdID = ?';
        return $sql;
    }

    public static function AtualizarSaldoProdutoSQL()
    {
        return "UPDATE tb_produto SET ProdEstoque = ProdEstoque - ? WHERE ProdID = ?";
    }
    public static function EncerramentoLoteSQL()
    {
        return "UPDATE tb_lote SET valor_total = ?, status = ? Where id = ? ";
    }
}

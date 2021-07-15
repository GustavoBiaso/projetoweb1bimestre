<?php
class Form
{
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("nome", "");
    $form->set("classificacao", "");
    $form->set("descricao", "");
    $retorno["msg"] = $form->saida();
    return $retorno;
  }

  public function salvar()
  {
    if (isset($_POST["nome"]) && isset($_POST["classificacao"]) && isset($_POST["descricao"])) {
      try {
        $conexao = Transaction::get();
        $nome = $conexao->quote($_POST["nome"]);
        $classificacao = $conexao->quote($_POST["classificacao"]);
        $descricao = $conexao->quote($_POST["descricao"]);
        $crud = new Crud();
        if (empty($_POST["id"])) {
          $retorno = $crud->insert(
            "softwares",
            "nome,classificacao,descricao",
            "{$nome},{$classificacao},{$descricao}"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $retorno = $crud->update(
            "softwares",
            "nome={$nome}, classificacao={$classificacao}, descricao={$descricao}",
            "id={$id}"
          );
        }
      } catch (Exception $e) {
        $retorno["msg"] = "Ocorreu um erro! " . $e->getMessage();
        $retorno["erro"] = TRUE;
      }
    } else {
      $retorno["msg"] = "Preencha todos os campos! ";
      $retorno["erro"] = TRUE;
    }
    return $retorno;
  }

  public function __destruct()
  {
    Transaction::close();
  }
}
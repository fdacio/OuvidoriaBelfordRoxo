<?php
/*
 * classe TSqlInstruction
 * Esta classe provê os métodos em comum entre todas instruções
 * SQL (SELECT, INSERT, DELETE e UPDATE)
 */
abstract class TSqlInstruction
{
    protected $sql;		     // armazena a instrução SQL
    protected $criteria;	 // armazena o objeto critério
    protected $entity;
    
    /*
     * método setEntity()
     * define o nome da entidade (tabela) manipulada pela instrução SQL
     * @param $entity = tabela
     */
    final public function setEntity($entity)
    {
        $this->entity = $entity;
    }
    
    /*
     * m�todo getEntity()
     * retorna o nome da entidade (tabela)
     */
    final public function getEntity()
    {
        return $this->entity;
    }
    
    /*
     * m�todo setCriteria()
     * Define um crit�rio de sele��o dos dados atrav�s da composi��o de um objeto
     * do tipo TCriteria, que oferece uma interface para defini��o de crit�rios
     * @param $criteria = objeto do tipo TCriteria
     */
    public function setCriteria(TCriteria $criteria)
    {
        $this->criteria = $criteria;
    }
    
    /*
     * m�todo getInstruction()
     * declarando-o como <abstract> obrigamos sua declara��o nas classes filhas,
     * uma vez que seu comportamento ser� distinto em cada uma delas, configurando polimorfismo.
     */
    abstract function getInstruction();
}
?>
<?php

/**
 * Interface para clases modelo
 *
 */
interface IModel {
	/**
	 * Carga los datos del RecordSet y los prepara para el despliegue. 
	 *
	 */
	public function load();
	/**
	 * Ejecuta queries INSERT.
	 *
	 */
	public function add();
	/**
	 * Ejecuta queries UPDATE.
	 *
	 */
	public function update();
	/**
	 * Ejecuta queries DELETE.
	 *
	 */
	public function delete();
	/**
	 * Calcula el numero de filas totales de la RecordSet para paginaci&oacute;.
	 *
	 */
	public function countAll();
	/**
	 * Obtiene el pr&oacute;ximo registro.
	 *
	 */
	public function next();
	/**
	 * Mueve el puntero al principio de RecordSet.
	 *
	 */
	public function reset();
	/**
	 * @return int n&uacute;mero de filas de RecordSet.
	 *
	 */
	public function getSize();
    /**
     * @return int OFFSET.
     *
     */
    public function getStart();
	/**
	 * @return int LIMIT.
	 *
	 */
	public function getLimit();
	/**
	 * @return DataOrder ORDER.
	 *
	 */
	public function getOrder();
	/**
	 * @return los IDS de la query a ejecutar.
	 *
	 */
	public function getId();
	/**
	 * Setea el puntero del primer registro a retornar.
	 *
	 * @param int $start
	 */
	public function setStart($start);
	/**
	 * Setea el n&uacute;mero m&aacute;cute de filas.
	 *
	 * @param int $limit
	 */
	public function setLimit($limit);
	/**
	 * A&ntilde;de ORDER BY a las queries.
	 *
	 * @param DataOrder $order
	 */
	public function addOrder($order);
}

?>
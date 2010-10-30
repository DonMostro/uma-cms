<?php

/**
 * Interface for model classes
 *
 */
interface IModel {
	/**
	 * Load data and prepare for retrieval. Usually executing SQL query.
	 *
	 */
	public function load();
	/**
	 * Perform data insertion routines.
	 *
	 */
	public function add();
	/**
	 * Perform data update routines.
	 *
	 */
	public function update();
	/**
	 * Perform data removal routines.
	 *
	 */
	public function delete();
	/**
	 * In data paging, calculate total amount of data.
	 *
	 */
	public function countAll();
	/**
	 * Get next record.
	 *
	 */
	public function next();
	/**
	 * Move pointer to the begining of data set.
	 *
	 */
	public function reset();
	/**
	 * @return int Number of rows in data set.
	 *
	 */
	public function getSize();
    /**
     * @return int In data paging, starting row in data set.
     *
     */
    public function getStart();
	/**
	 * @return int In data paging, number of rows per page.
	 *
	 */
	public function getLimit();
	/**
	 * @return DataOrder Last sorting field.
	 *
	 */
	public function getOrder();
	/**
	 * @return mixed Arbitral ID or an array of IDs of the criteria.
	 *
	 */
	public function getId();
	/**
	 * Set starting row in the data set for paging.
	 *
	 * @param int $start
	 */
	public function setStart($start);
	/**
	 * Set number of rows in data set for paging.
	 *
	 * @param int $limit
	 */
	public function setLimit($limit);
	/**
	 * Add sorting directive.
	 *
	 * @param DataOrder $order
	 */
	public function addOrder($order);
}

?>
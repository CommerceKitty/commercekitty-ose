<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 * Data Feeds are used for:
 *   - product imports/exports
 *   - inventory imports/exports
 *   - public file used for 3rd parties to pull inventory (google merchant)
 */
interface DataFeedInterface
{
    const TYPE_CSV = 'csv';
    const TYPE_XML = 'xml';

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * @return DataFeedColumnInterface[]
     */
    public function getDataFeedColumns();
}

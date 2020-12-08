<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use Doctrine\Common\Collections\ArrayCollection;
use DateTimeInterface;

class Order implements OrderInterface
{
    protected $channel;
    protected $orderItems;
    protected $id;
    protected $fid;
    protected $displayId;
    protected $createdAt;

    /**
     */
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    /**
     */
    public function __toString()
    {
        return (string) $this->channel.' - '.$this->displayId;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    /**
     */
    public function setChannel(ChannelInterface $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getFid(): string
    {
        return $this->fid;
    }

    /**
     */
    public function setFid(string $fid): self
    {
        $this->fid = $fid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayId(): string
    {
        return $this->displayId;
    }

    /**
     */
    public function setDispalyId(string $displayId): self
    {
        $this->displayId = $displayId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderItems(): iterable
    {
        return $this->orderItems;
    }

    /**
     */
    public function hasOrderItem(OrderItemInterface $item): bool
    {
        return $this->orderItems->exists(function ($key, $value) use ($item) {
            return
                ($value->getProduct()->getSku() === $item->getProduct()->getSku())
                && ($value->getQuantity() === $item->getQuantity())
            ;
        });
    }

    /**
     */
    public function addOrderItem(OrderItemInterface $item): self
    {
        if (!$this->hasOrderItem($item)) {
            $item->setOrder($this);
            $this->orderItems[] = $item;
        }

        return $this;
    }
}

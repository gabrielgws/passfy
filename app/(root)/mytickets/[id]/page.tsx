import React from 'react'
import Link from 'next/link'
import { Button } from '@/components/ui/button'
import { getOrdersByUser } from '@/lib/actions/order.actions'
import { IOrder, IOrderItem } from '@/lib/database/models/order.model'
import { SearchParamProps } from '@/types'
import { auth } from '@clerk/nextjs'

interface IOrderItemProps {
  orderItem: IOrderItem
  buyer: IOrder
}

const MyTicketsDetails = async ({ searchParams }: SearchParamProps) => {
  const { sessionClaims } = auth();
  const userId = sessionClaims?.userId as string;

  const ordersPage = Number(searchParams?.ordersPage) || 1;
  const eventsPage = Number(searchParams?.eventsPage) || 1;

  const orders = await getOrdersByUser({ userId, page: ordersPage})
  const myTicket = orders?.data.map((orderItem: IOrderItemProps) => orderItem);

  return (
    <>
      {/* My Tickets */}
      <section className="bg-primary-50 bg-dotted-pattern bg-cover bg-center py-5 md:py-10">
        <div className="wrapper flex items-center justify-center sm:justify-between">
          <h3 className='h3-bold text-center sm:text-left'>Meu ticket</h3>
          {/* <Button asChild size="lg" className="button hidden sm:flex"> */}
          <Button asChild size="lg" className="button sm:flex">
            <Link href="/#events">
              Baixar como PDF
            </Link>
          </Button>
        </div>
      </section>

      {myTicket.map((ticket: IOrderItem, index: number) => (
        <section className="wrapper my-8" key={index}>
          <div className='flex flex-col gap-5'>
            <h3 className='text-xl font-bold sm:text-left'>
              Pedido nº: <span className='text-xl font-medium sm:text-left'>{ticket._id}</span>
            </h3>

            <h4>Comprador: {ticket.buyer}</h4>
          </div>
        </section>
      ))}

      {/* <section className="wrapper my-8">
        <h1>Código do ticket: {myTicket._id}</h1>
        <CollectionMyTickets
          myTickets={myTickets}
          data={orderedEvents}
          emptyTitle="No event tickets purchased yet"
          emptyStateSubtext="No worries - plenty of exciting events to explore!"
          collectionType="My_Tickets"
          limit={3}
          page={ordersPage}
          urlParamName="ordersPage"
          totalPages={orders?.totalPages}
          />
      </section> */}
    </>
  )
}

export default MyTicketsDetails
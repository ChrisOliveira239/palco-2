export type PricingType = 'free' | 'fixed'

export type EventSession = {
  id: number
  startAt: string
  endAt: string | null
  pricingType: PricingType
  price: string | null
  capacity: number | null
}

export type EventSessionFormValues = {
  startAt: string
  endAt: string
  pricingType: PricingType
  price: string
  capacity: string
}

export type EventSessionFormErrorResponse = {
  message: string
  errors?: Record<string, string[]>
}

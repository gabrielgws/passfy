import React, { startTransition, useState } from 'react'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog"
import { ICategory } from '@/lib/database/models/category.model'
import { Input } from '../ui/input'
import { Button } from '../ui/button'


type DropdownProps = {
  value?: string
  onChangeHandler?: (value: string) => void
}

const Dropdown = ({ value, onChangeHandler }: DropdownProps) => {
  const [categories, setCategories] = useState<ICategory[]>([]);
  const [newCategory, setNewCategory] = useState('');

  const handleAddCategory = () => {

  }

  return (
    <Select onValueChange={onChangeHandler} defaultValue={value}>
      <SelectTrigger className="select-field">
        <SelectValue placeholder="Category" />
      </SelectTrigger>
      <SelectContent>
        {categories.length > 0 && categories.map((category) =>(
          <SelectItem key={category._id} value={category._id}
            className="select-item p-regular-14"
          >
            {category.name}
          </SelectItem>
        ))}

        <Dialog>
          <DialogTrigger className="p-medium-14 flex w-full rounded-sm py-3 pl-8 text-primary-500 hover:bg-primary-50 focus:text-primary-500">
            Open
          </DialogTrigger>
          <DialogContent className="bg-white">
            <DialogHeader>
              <DialogTitle>New Category</DialogTitle>
              <DialogDescription >
                <Input type='text' placeholder='Category name'  className="input-field mt-3"
                  onChange={(e) => setNewCategory(e.target.value)}
                />
              </DialogDescription>
            </DialogHeader>

            <DialogFooter className='flex flex-col md:flex-row gap-5'>
              <DialogClose>Cancel</DialogClose>
              <Button onClick={() => startTransition(handleAddCategory)}>Add</Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </SelectContent>
    </Select>
  )
}

export default Dropdown